<?php

namespace App\Services;

use RuntimeException;

class UpdatePublisher
{
    public function publish(array $options): array
    {
        $manifestPath = $this->absolutePath($options['manifest'] ?? 'storage/update-packages/manifest.json');
        $manifest = $this->readManifest($manifestPath);
        $packagePath = $this->resolvePackagePath($manifestPath, $manifest, $options);
        $packageSha = hash_file('sha256', $packagePath);

        if (!empty($manifest['sha256']) && !hash_equals(strtolower($manifest['sha256']), strtolower($packageSha))) {
            throw new RuntimeException("Package checksum does not match manifest sha256.");
        }

        $ftp = $this->resolveFtpConfig($options);
        $files = [
            [
                'local' => $packagePath,
                'remote' => $this->remoteFile($ftp['remote_path'], basename($packagePath)),
            ],
            [
                'local' => $manifestPath,
                'remote' => $this->remoteFile($ftp['remote_path'], basename($manifestPath)),
            ],
        ];

        if (!empty($options['dry_run'])) {
            return [
                'dry_run' => true,
                'version' => $manifest['version'] ?? null,
                'sha256' => $packageSha,
                'ftp' => $this->safeFtpConfig($ftp),
                'files' => $files,
            ];
        }

        if (empty($ftp['password'])) {
            throw new RuntimeException("Missing FTP password. Set JANNAH_UPDATE_FTP_PASSWORD or enter it at the prompt.");
        }

        if (!extension_loaded('ftp')) {
            throw new RuntimeException("The PHP FTP extension is not installed.");
        }

        $connection = $this->connect($ftp);

        try {
            $this->ensureRemoteDirectory($connection, $ftp['remote_path']);

            foreach ($files as $file) {
                $this->uploadFile($connection, $file['local'], $file['remote']);
                $this->verifyRemoteSize($connection, $file['local'], $file['remote']);
            }

            $this->verifyRemoteManifest($connection, $manifestPath, $this->remoteFile($ftp['remote_path'], basename($manifestPath)));
        } finally {
            ftp_close($connection);
        }

        return [
            'dry_run' => false,
            'version' => $manifest['version'] ?? null,
            'sha256' => $packageSha,
            'ftp' => $this->safeFtpConfig($ftp),
            'files' => $files,
        ];
    }

    private function readManifest(string $path): array
    {
        if (!is_file($path)) {
            throw new RuntimeException("Manifest file was not found: {$path}");
        }

        $manifest = json_decode(file_get_contents($path), true, 512, JSON_THROW_ON_ERROR);

        if (empty($manifest['version']) || empty($manifest['download'])) {
            throw new RuntimeException("Manifest must contain version and download fields.");
        }

        return $manifest;
    }

    private function resolvePackagePath(string $manifestPath, array $manifest, array $options): string
    {
        if (!empty($options['package'])) {
            $packagePath = $this->absolutePath($options['package']);
        } else {
            $downloadPath = parse_url($manifest['download'], PHP_URL_PATH);
            $packagePath = dirname($manifestPath) . DIRECTORY_SEPARATOR . basename($downloadPath ?: '');
        }

        if (!is_file($packagePath)) {
            throw new RuntimeException("Package file was not found: {$packagePath}");
        }

        return $packagePath;
    }

    private function resolveFtpConfig(array $options): array
    {
        $config = config('services.jannah_updates.ftp', []);
        $bookmarkPath = $this->absolutePath($options['bookmark'] ?? $config['bookmark'] ?? '');
        $bookmark = is_file($bookmarkPath) ? $this->readDuckBookmark($bookmarkPath) : [];

        $ssl = $options['ssl'] ?? $config['ssl'] ?? ($bookmark['protocol'] ?? '') === 'ftps';
        $passive = $options['passive'] ?? $config['passive'] ?? true;

        return [
            'host' => $this->firstFilled($options['host'] ?? null, $config['host'] ?? null, $bookmark['hostname'] ?? null),
            'username' => $this->firstFilled($options['username'] ?? null, $config['username'] ?? null, $bookmark['username'] ?? null),
            'password' => $this->firstFilled($options['password'] ?? null, $config['password'] ?? null),
            'port' => (int) $this->firstFilled($options['port'] ?? null, $config['port'] ?? null, $bookmark['port'] ?? null, $ssl ? 990 : 21),
            'remote_path' => $this->firstFilled($options['remote_path'] ?? null, $config['remote_path'] ?? null, '/'),
            'ssl' => filter_var($ssl, FILTER_VALIDATE_BOOLEAN),
            'passive' => filter_var($passive, FILTER_VALIDATE_BOOLEAN),
            'timeout' => (int) $this->firstFilled($options['timeout'] ?? null, $config['timeout'] ?? null, 30),
            'bookmark' => $bookmarkPath,
        ];
    }

    private function firstFilled(...$values)
    {
        foreach ($values as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return $value;
            }
        }

        return null;
    }

    private function readDuckBookmark(string $path): array
    {
        $xml = simplexml_load_file($path, 'SimpleXMLElement', LIBXML_NONET);

        if (!$xml || !isset($xml->dict)) {
            throw new RuntimeException("Invalid Cyberduck bookmark: {$path}");
        }

        $items = $xml->dict->children();
        $data = [];

        for ($i = 0; $i < count($items) - 1; $i += 2) {
            $key = strtolower((string) $items[$i]);
            $data[$key] = trim((string) $items[$i + 1]);
        }

        return $data;
    }

    private function connect(array $ftp)
    {
        if (empty($ftp['host']) || empty($ftp['username'])) {
            throw new RuntimeException("FTP host and username are required.");
        }

        $connection = $ftp['ssl']
            ? @ftp_ssl_connect($ftp['host'], $ftp['port'], $ftp['timeout'])
            : @ftp_connect($ftp['host'], $ftp['port'], $ftp['timeout']);

        if (!$connection) {
            throw new RuntimeException("Could not connect to FTP host {$ftp['host']}:{$ftp['port']}.");
        }

        if (!@ftp_login($connection, $ftp['username'], $ftp['password'])) {
            ftp_close($connection);

            throw new RuntimeException("FTP login failed for {$ftp['username']}.");
        }

        ftp_pasv($connection, $ftp['passive']);

        return $connection;
    }

    private function ensureRemoteDirectory($connection, string $remotePath): void
    {
        $remotePath = trim($remotePath, '/');

        if ($remotePath === '') {
            return;
        }

        $originalDirectory = ftp_pwd($connection);

        foreach (explode('/', $remotePath) as $directory) {
            if ($directory === '') {
                continue;
            }

            if (@ftp_chdir($connection, $directory)) {
                continue;
            }

            if (!@ftp_mkdir($connection, $directory) || !@ftp_chdir($connection, $directory)) {
                throw new RuntimeException("Could not create or enter remote FTP directory: {$directory}");
            }
        }

        ftp_chdir($connection, $originalDirectory ?: '/');
    }

    private function uploadFile($connection, string $localPath, string $remotePath): void
    {
        if (!@ftp_put($connection, $remotePath, $localPath, FTP_BINARY)) {
            throw new RuntimeException("Could not upload {$localPath} to {$remotePath}.");
        }
    }

    private function verifyRemoteSize($connection, string $localPath, string $remotePath): void
    {
        $remoteSize = ftp_size($connection, $remotePath);

        if ($remoteSize >= 0 && $remoteSize !== filesize($localPath)) {
            throw new RuntimeException("Uploaded size mismatch for {$remotePath}.");
        }
    }

    private function verifyRemoteManifest($connection, string $localPath, string $remotePath): void
    {
        $temporaryPath = tempnam(sys_get_temp_dir(), 'jannah-update-manifest-');

        try {
            if (!@ftp_get($connection, $temporaryPath, $remotePath, FTP_BINARY)) {
                throw new RuntimeException("Could not download uploaded manifest for verification.");
            }

            if (!hash_equals(hash_file('sha256', $localPath), hash_file('sha256', $temporaryPath))) {
                throw new RuntimeException("Uploaded manifest verification failed.");
            }
        } finally {
            if (is_file($temporaryPath)) {
                @unlink($temporaryPath);
            }
        }
    }

    private function remoteFile(string $remotePath, string $filename): string
    {
        $remotePath = trim($remotePath, '/');

        return $remotePath === '' ? $filename : "{$remotePath}/{$filename}";
    }

    private function absolutePath(string $path): string
    {
        if ($path === '') {
            return '';
        }

        if (str_starts_with($path, DIRECTORY_SEPARATOR)) {
            return $path;
        }

        return base_path($path);
    }

    private function safeFtpConfig(array $ftp): array
    {
        unset($ftp['password']);

        return $ftp;
    }
}
