<?php
session_start();

require __DIR__.'/global.php';

if ($installed) {
    header('Location: /');
    exit();
}

// Plain-PHP installer. We intentionally do NOT bootstrap Laravel here —
// service providers (Plugin, Theme, etc.) try to query the database on boot,
// which would crash before the user has entered DB credentials.

// Give long-running shell-outs (migrate, seed) room to finish on slow hosts.
@set_time_limit(300);
@ini_set('max_execution_time', '300');
@ini_set('memory_limit', '256M');

// Step 3 streams progress to the browser as artisan output arrives.
@ini_set('output_buffering', '0');
@ini_set('zlib.output_compression', '0');

function installer_log_path() {
    $path = __DIR__ . '/../../storage/logs/installer.log';
    $dir  = dirname($path);
    if (!is_dir($dir)) @mkdir($dir, 0775, true);
    return $path;
}

function installer_log($message, $context = []) {
    $line = '[' . date('Y-m-d H:i:s') . '] ' . $message;
    if (!empty($context)) {
        $line .= ' ' . json_encode($context, JSON_UNESCAPED_SLASHES);
    }
    @file_put_contents(installer_log_path(), $line . "\n", FILE_APPEND);
}

function get_db_config() {
    // Read .env directly so we don't depend on the Laravel container.
    $envPath = __DIR__ . '/../../.env';
    $vars = [];
    if (file_exists($envPath)) {
        foreach (file($envPath, FILE_IGNORE_NEW_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#')) continue;
            if (!preg_match('/^([A-Z0-9_]+)\s*=\s*(.*)$/', $line, $m)) continue;
            $val = $m[2];
            if (preg_match('/^"(.*)"$/', $val, $q)) {
                $val = stripcslashes($q[1]);
            } elseif (preg_match("/^'(.*)'$/", $val, $q)) {
                $val = $q[1];
            }
            $vars[$m[1]] = $val;
        }
    }
    return [
        'host'     => $vars['DB_HOST'] ?? 'localhost',
        'port'     => $vars['DB_PORT'] ?? '3306',
        'database' => $vars['DB_DATABASE'] ?? '',
        'username' => $vars['DB_USERNAME'] ?? '',
        'password' => $vars['DB_PASSWORD'] ?? '',
    ];
}

function alert($message, $type = 'info') {
    $_SESSION['alert'] = ['message' => $message, 'type' => $type];
}

function show_alert() {
    if (isset($_SESSION['alert'])) {
        $alert = $_SESSION['alert'];
        echo "<div class='alert alert-$alert[type]'>$alert[message]</div>";
        unset($_SESSION['alert']);
    }
}

function update_env($data = []) {
    $envPath = __DIR__ . '/../../.env';
    if (!file_exists($envPath)) {
        $example = __DIR__ . '/../../.env.example';
        if (file_exists($example)) copy($example, $envPath);
        else file_put_contents($envPath, "");
    }
    $envContent = file_get_contents($envPath);

    foreach ($data as $key => $value) {
        // Quote values containing whitespace, '#', or shell-special chars so .env parses cleanly
        if ($value !== '' && preg_match('/[\s#"\'\\\\$]/', (string)$value)) {
            $quoted = '"' . addcslashes((string)$value, "\"\\\$") . '"';
        } else {
            $quoted = $value;
        }
        $line = $key . '=' . $quoted;
        $pattern = "/^" . preg_quote($key, '/') . "=.*/m";
        if (preg_match($pattern, $envContent)) {
            $envContent = preg_replace($pattern, $line, $envContent);
        } else {
            $envContent .= "\n" . $line;
        }
    }

    $envContent = rtrim($envContent) . "\n";
    file_put_contents($envPath, $envContent);
}

function update_global() {
    $globalPath = __DIR__ . '/global.php';

    if (!file_exists($globalPath)) {
        throw new Exception("File not found: $globalPath");
    }

    $globalContent = file_get_contents($globalPath);
    if ($globalContent === false) {
        throw new Exception("Failed to read file: $globalPath");
    }

    $newContent = preg_replace("/\\\$installed\s*=\s*false;/", "\\\$installed = true;", $globalContent);
    if ($newContent === null) {
        throw new Exception("Failed to update content in file: $globalPath");
    }

    $result = file_put_contents($globalPath, $newContent);
    if ($result === false) {
        throw new Exception("Failed to write file: $globalPath");
    }
}
function php_binary() {
    static $cached = null;
    if ($cached !== null) return $cached;

    $candidates = [];

    // PHP_BINARY is whatever binary is currently executing. Under cPanel/FastCGI
    // web requests that's PHP-CGI, which hangs when invoked as CLI — so derive
    // the matching CLI binary by dropping the "-cgi" suffix.
    if (defined('PHP_BINARY') && PHP_BINARY) {
        if (str_contains(PHP_BINARY, '-cgi')) {
            $candidates[] = preg_replace('/-cgi$/', '', PHP_BINARY);
        } else {
            $candidates[] = PHP_BINARY;
        }
    }

    // Common cPanel EasyApache paths (newest PHP first).
    foreach (['ea-php84', 'ea-php83', 'ea-php82', 'ea-php81'] as $ea) {
        $candidates[] = "/opt/cpanel/{$ea}/root/usr/bin/php";
    }

    $candidates[] = '/usr/local/bin/php';
    $candidates[] = '/usr/bin/php';
    $candidates[] = 'php';

    foreach ($candidates as $candidate) {
        if (!$candidate) continue;
        if (str_contains($candidate, '-cgi')) continue; // CGI binaries hang when invoked as CLI
        if (@is_executable($candidate)) {
            return $cached = $candidate;
        }
    }

    return $cached = (PHP_BINARY ?: 'php');
}

function artisan_path() {
    $artisan = realpath(__DIR__ . '/../../artisan');
    if (!$artisan) throw new Exception("artisan not found");
    return $artisan;
}

/**
 * Run `php artisan <command> --force`, streaming stdout/stderr into the installer
 * log so we can see exactly where things hang. Returns the captured output.
 */
function run_command($command) {
    $php = php_binary();
    $artisan = artisan_path();
    $cmd = escapeshellarg($php) . ' ' . escapeshellarg($artisan) . ' ' . $command . ' --force';

    installer_log("RUN  $cmd");

    if (function_exists('proc_open')) {
        $descriptors = [
            0 => ['pipe', 'r'],
            1 => ['pipe', 'w'],
            2 => ['pipe', 'w'],
        ];
        $proc = @proc_open($cmd . ' 2>&1', $descriptors, $pipes, dirname($artisan));
        if (!is_resource($proc)) {
            installer_log("FAIL proc_open returned no resource — proc_open may be disabled");
            return run_command_shell_exec($cmd);
        }
        fclose($pipes[0]);
        $output = '';
        while (!feof($pipes[1])) {
            $chunk = fgets($pipes[1]);
            if ($chunk === false) break;
            $output .= $chunk;
            installer_log("OUT  " . rtrim($chunk));
        }
        fclose($pipes[1]);
        if (isset($pipes[2])) fclose($pipes[2]);
        $exit = proc_close($proc);
        installer_log("EXIT $exit", ['cmd' => $command]);
        if ($exit !== 0) {
            throw new Exception("artisan $command failed (exit $exit). See storage/logs/installer.log for details.\n\nLast output:\n" . $output);
        }
        return $output;
    }

    return run_command_shell_exec($cmd);
}

function run_command_shell_exec($cmd) {
    if (!function_exists('shell_exec')) {
        installer_log("FAIL shell_exec is disabled");
        throw new Exception("Both proc_open and shell_exec are disabled on this host. Enable one of them, or run `php artisan migrate --force && php artisan db:seed --force` from SSH and re-run the installer.");
    }
    $output = shell_exec($cmd . ' 2>&1');
    installer_log("OUT  " . ($output ?? '(null)'));
    if ($output === null) {
        throw new Exception("shell_exec returned null — shell_exec may be disabled.");
    }
    // shell_exec has no exit code; rely on output heuristics
    if (preg_match('/(?:^|\b)(error|exception|fatal|sqlstate)/i', $output)) {
        throw new Exception("artisan command appears to have failed:\n" . $output);
    }
    return $output;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['step'])) {
        $step = intval($_POST['step']);

        installer_log("==== STEP $step start ====");

        if ($step == 1) {
            $db_host = $_POST['db_host'];
            $db_name = $_POST['db_name'];
            $db_user = $_POST['db_user'];
            $db_pass = $_POST['db_pass'];

            try {
                update_env([
                    'DB_CONNECTION' => 'mysql',
                    'DB_HOST' => $db_host,
                    'DB_PORT' => '3306',
                    'DB_DATABASE' => $db_name,
                    'DB_USERNAME' => $db_user,
                    'DB_PASSWORD' => $db_pass,
                ]);

                alert('Database connection successful! Proceed to the next step.', 'success');
                header('Location: index.php?step=2');
                exit;

            } catch (Exception $e) {
                alert('Error: ' . $e->getMessage(), 'error');
            }
        }

        if ($step == 2) {
            $mail_host = $_POST['mail_host'];
            $mail_port = $_POST['mail_port'];
            $mail_user = $_POST['mail_user'];
            $mail_pass = $_POST['mail_pass'];
            $mail_from = $_POST['mail_from'];
            $mail_name = $_POST['mail_name'];

            update_env([
                'MAIL_MAILER' => 'smtp',
                'MAIL_HOST' => $mail_host,
                'MAIL_PORT' => $mail_port,
                'MAIL_USERNAME' => $mail_user,
                'MAIL_PASSWORD' => $mail_pass,
                'MAIL_ENCRYPTION' => 'tls',
                'MAIL_FROM_ADDRESS' => $mail_from,
                'MAIL_FROM_NAME' => $mail_name,
            ]);

            alert('Email setup successful! Proceed to the next step.', 'success');
            header('Location: index.php?step=3');
            exit;
        }

        if ($step == 3) {
            $app_url   = rtrim(trim($_POST['app_url']), '/');
            $site_name = trim($_POST['site_name'] ?? '') ?: 'Jannah VTU';

            try {
                // Generate APP_KEY in pure PHP — exactly what `php artisan key:generate`
                // does internally, but without booting Laravel (which would hang if any
                // service provider can't reach the DB or has slow boot logic).
                installer_log("step 3: generating APP_KEY (pure PHP)");
                $key = 'base64:' . base64_encode(random_bytes(32));
                installer_log("step 3: APP_KEY generated", ['key_len' => strlen($key)]);

                update_env([
                    'APP_URL' => $app_url,
                    'APP_KEY' => $key,
                ]);
                installer_log("step 3: .env updated with APP_URL and APP_KEY");

                // Diagnostic snapshot before we shell out, so we can spot env issues fast.
                installer_log("step 3: environment", [
                    'php_binary'        => php_binary(),
                    'artisan'           => artisan_path(),
                    'proc_open'         => function_exists('proc_open'),
                    'shell_exec'        => function_exists('shell_exec'),
                    'max_execution_time'=> ini_get('max_execution_time'),
                    'memory_limit'      => ini_get('memory_limit'),
                ]);

                // Sanity check: can we even run artisan at all? Direct call so we
                // don't append --force (which --version rejects).
                installer_log("step 3: probing artisan with --version");
                $probeCmd = escapeshellarg(php_binary()) . ' ' . escapeshellarg(artisan_path()) . ' --version 2>&1';
                $probeOut = (string) @shell_exec($probeCmd);
                installer_log("step 3: --version output", ['out' => trim($probeOut)]);
                if ($probeOut === '' || stripos($probeOut, 'laravel') === false) {
                    throw new Exception("artisan --version did not return a Laravel version. Got: " . substr($probeOut, 0, 300));
                }
                installer_log("step 3: artisan --version succeeded");

                // Use migrate:fresh so the installer is idempotent — if a previous
                // run partially created tables, fresh drops them all and starts clean.
                // Safe here because the installer locks itself behind $installed=true
                // after step 4, so this only ever runs during initial install.
                installer_log("step 3: starting migrate:fresh");
                run_command('migrate:fresh');
                installer_log("step 3: migrate:fresh complete");

                installer_log("step 3: starting db:seed");
                run_command('db:seed');
                installer_log("step 3: db:seed complete");

                // Override the seeded site_name / site_url with what the user supplied,
                // so the live Settings reflect their input rather than the defaults.
                try {
                    $dbConfig = get_db_config();
                    $pdo = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['database']}", $dbConfig['username'], $dbConfig['password']);
                    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $upd = $pdo->prepare("UPDATE jannah_options SET opt_value = ? WHERE opt_key = ?");
                    $upd->execute([$site_name, 'site_name']);
                    $upd->execute([$app_url,   'site_url']);
                    installer_log("step 3: site_name/site_url set from user input", ['site_name' => $site_name, 'site_url' => $app_url]);
                } catch (PDOException $e) {
                    // Non-fatal — operator can still set these in admin Settings.
                    installer_log("step 3: could not set site options: " . $e->getMessage());
                }

                alert('Migration and seeding completed! Please set up admin credentials.', 'success');
                installer_log("==== STEP 3 done ====");
                header('Location: index.php?step=4');
                exit;

            } catch (Exception $e) {
                installer_log("step 3 EXCEPTION: " . $e->getMessage());
                alert('Error during installation: ' . $e->getMessage(), 'error');
            }
        }

        if ($step == 4) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);

            try {
                $dbConfig = get_db_config();
                $pdo = new PDO("mysql:host={$dbConfig['host']};dbname={$dbConfig['database']}", $dbConfig['username'], $dbConfig['password']);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare("INSERT INTO jannah_admins (username, email, password, status, role) VALUES (?, ?, ?, ?, ?)");
                $stmt->execute([$username, $email, $password, 1, 'admin']);

                update_global();

                alert('Admin user created successfully! Installation is complete.', 'success');
                header('Location: index.php?step=5');
                exit;

            } catch (PDOException $e) {
                alert('Error: ' . $e->getMessage(), 'error');
            }
        }
    }
}
?>
<?php
$steps = [
    1 => [
        'title'  => 'Database',
        'desc'   => 'Connect Jannah VTU to your MySQL database.',
        'hidden' => ['db_host' => 'localhost'],
        'fields' => [
            'db_name' => 'Database Name',
            'db_user' => 'Database User',
            'db_pass' => 'Database Password',
        ],
    ],
    2 => [
        'title'  => 'Email',
        'desc'   => 'Set up the SMTP account used to send mail.',
        'fields' => [
            'mail_host' => 'Mail Host',
            'mail_port' => 'Mail Port',
            'mail_user' => 'Mail Username',
            'mail_pass' => 'Mail Password',
            'mail_from' => 'Mail From Address',
            'mail_name' => 'Mail From Name',
        ],
    ],
    3 => [
        'title'  => 'Application',
        'desc'   => 'Name your site and set its URL. We will run migrations next.',
        'fields' => [
            'site_name' => 'Site Name',
            'app_url'   => 'Application URL',
        ],
    ],
    4 => [
        'title'  => 'Admin',
        'desc'   => 'Create the administrator account.',
        'fields' => [
            'username' => 'Admin Username',
            'email'    => 'Admin Email',
            'password' => 'Admin Password',
        ],
    ],
];

$currentStep = (int) ($_GET['step'] ?? 1);
$totalSteps  = count($steps);
$placeholders = [
    'db_name' => 'jannah_db', 'db_user' => 'jannah_user', 'db_pass' => '••••••••',
    'mail_host' => 'mail.example.com', 'mail_port' => '465', 'mail_user' => 'hello@example.com',
    'mail_pass' => '••••••••', 'mail_from' => 'hello@example.com', 'mail_name' => 'Jannah VTU',
    'site_name' => 'Jannah VTU', 'app_url' => 'https://app.example.com',
    'username' => 'admin', 'email' => 'you@example.com', 'password' => 'Choose a strong password',
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Install Jannah VTU</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,600;12..96,700&family=Inter:wght@400;500;600&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#f6f3eb; --bg-2:#efeadc; --ink:#0e1a14; --ink-2:#2b3a32; --muted:#6b7a72;
            --line:rgba(14,26,20,.10); --line-2:rgba(14,26,20,.18);
            --emerald:#0d4f3c; --emerald-2:#0a3d2e; --emerald-soft:#e3ece6;
            --gold:#c8a26b; --card:#fffdf6;
            --shadow:0 8px 24px -8px rgba(14,26,20,.16),0 1px 2px rgba(14,26,20,.06);
            --shadow-lg:0 24px 60px -20px rgba(14,26,20,.28),0 2px 6px rgba(14,26,20,.06);
            --radius-lg:22px;
            --font-display:"Bricolage Grotesque",ui-sans-serif,system-ui,sans-serif;
            --font-body:"Inter",ui-sans-serif,system-ui,sans-serif;
            --font-mono:"JetBrains Mono",ui-monospace,Menlo,monospace;
        }
        *{box-sizing:border-box;}
        html,body{margin:0;padding:0;background:var(--bg);color:var(--ink);font-family:var(--font-body);font-size:16px;line-height:1.55;-webkit-font-smoothing:antialiased;}
        body{min-height:100vh;display:grid;place-items:center;padding:40px 20px;position:relative;overflow-x:hidden;}
        body::before{content:"";position:fixed;inset:-10% -10% auto auto;width:55%;height:70%;background:radial-gradient(ellipse at 70% 30%,color-mix(in oklab,var(--emerald) 16%,transparent),transparent 60%);filter:blur(20px);pointer-events:none;}
        .shell{width:100%;max-width:540px;position:relative;z-index:1;}
        .brand{display:flex;align-items:center;gap:11px;font-family:var(--font-display);font-weight:700;font-size:22px;letter-spacing:-.02em;justify-content:center;margin-bottom:26px;}
        .brand-mark{width:34px;height:34px;border-radius:9px;background:var(--emerald);position:relative;display:grid;place-items:center;box-shadow:inset 0 -8px 16px rgba(0,0,0,.25),0 4px 10px -4px var(--emerald);}
        .brand-mark::before{content:"";position:absolute;inset:7px;background:radial-gradient(circle at 30% 30%,var(--gold),transparent 60%);border-radius:50%;}
        .brand-mark::after{content:"";position:absolute;left:50%;top:50%;width:10px;height:10px;border:2px solid var(--bg);border-right:0;border-bottom:0;transform:translate(-50%,-55%) rotate(45deg);border-radius:2px;}
        .brand small{font-family:var(--font-mono);font-size:9px;opacity:.55;letter-spacing:.12em;text-transform:uppercase;margin-left:2px;}
        .card{background:var(--card);border:1px solid var(--line);border-radius:var(--radius-lg);box-shadow:var(--shadow-lg);padding:32px 30px;}
        /* stepper */
        .stepper{display:flex;align-items:center;gap:0;margin-bottom:26px;}
        .stepper .node{display:flex;flex-direction:column;align-items:center;gap:7px;flex:0 0 auto;}
        .stepper .dot{width:30px;height:30px;border-radius:50%;display:grid;place-items:center;font-family:var(--font-mono);font-size:12px;border:1.5px solid var(--line-2);background:var(--card);color:var(--muted);transition:.2s;}
        .stepper .node.active .dot{background:var(--emerald);border-color:var(--emerald);color:#fff;box-shadow:0 4px 12px -4px var(--emerald);}
        .stepper .node.done .dot{background:var(--emerald-soft);border-color:var(--emerald);color:var(--emerald);}
        .stepper .node .lbl{font-size:10.5px;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:.06em;color:var(--muted);}
        .stepper .node.active .lbl{color:var(--ink);}
        .stepper .bar{flex:1 1 auto;height:1.5px;background:var(--line-2);margin:0 6px;position:relative;top:-10px;}
        .stepper .bar.filled{background:var(--emerald);}
        /* head */
        .head{margin-bottom:22px;}
        .kicker{font-family:var(--font-mono);font-size:11px;text-transform:uppercase;letter-spacing:.1em;color:var(--gold);}
        .head h1{font-family:var(--font-display);font-weight:700;font-size:26px;letter-spacing:-.02em;margin:6px 0 4px;}
        .head p{margin:0;color:var(--muted);font-size:14px;}
        /* alert */
        .alert{display:flex;gap:10px;align-items:flex-start;padding:13px 15px;border-radius:12px;font-size:13.5px;margin-bottom:20px;border:1px solid;line-height:1.45;}
        .alert.success{background:var(--emerald-soft);border-color:rgba(13,79,60,.25);color:var(--emerald-2);}
        .alert.error{background:#fbeae8;border-color:rgba(180,40,30,.25);color:#8c2c22;}
        .alert .ic{flex:0 0 auto;margin-top:1px;}
        /* fields */
        .field{display:flex;flex-direction:column;gap:6px;margin-bottom:15px;}
        .field label{font-size:12px;color:var(--muted);font-weight:500;font-family:var(--font-mono);text-transform:uppercase;letter-spacing:.04em;}
        .ctrl{display:flex;align-items:center;background:var(--bg-2);border:1px solid var(--line);border-radius:12px;padding:0 14px;transition:.15s;}
        .ctrl:focus-within{border-color:var(--emerald);background:var(--card);box-shadow:0 0 0 4px var(--emerald-soft);}
        .ctrl input{flex:1;border:0;outline:0;background:transparent;color:var(--ink);font-family:inherit;font-size:14.5px;height:48px;}
        .ctrl input::placeholder{color:var(--muted);opacity:.7;}
        /* button */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:9px;width:100%;height:52px;border-radius:999px;font-size:15px;font-weight:600;border:0;cursor:pointer;font-family:inherit;background:var(--emerald);color:#fff;box-shadow:0 6px 20px -8px var(--emerald),inset 0 1px 0 rgba(255,255,255,.16);transition:.15s;margin-top:6px;}
        .btn:hover{background:var(--emerald-2);}
        .btn:active{transform:translateY(1px);}
        /* complete */
        .done-mark{width:64px;height:64px;border-radius:50%;background:var(--emerald-soft);border:1.5px solid var(--emerald);display:grid;place-items:center;margin:0 auto 18px;color:var(--emerald);}
        .center{text-align:center;}
        .note{margin-top:18px;padding:12px 15px;background:var(--bg-2);border:1px solid var(--line);border-radius:12px;font-size:12.5px;color:var(--ink-2);font-family:var(--font-mono);}
        .note code{color:var(--emerald-2);}
        .foot{text-align:center;margin-top:18px;font-size:12px;color:var(--muted);font-family:var(--font-mono);letter-spacing:.04em;}
    </style>
</head>
<body>
<div class="shell">
    <div class="brand"><span class="brand-mark"></span> Jannah VTU <small>setup</small></div>

    <div class="card">
        <?php if ($currentStep >= 1 && $currentStep <= $totalSteps): ?>
            <div class="stepper">
                <?php foreach ($steps as $i => $meta): ?>
                    <?php $cls = $i < $currentStep ? 'done' : ($i === $currentStep ? 'active' : ''); ?>
                    <div class="node <?php echo $cls; ?>">
                        <span class="dot"><?php echo $i < $currentStep ? '✓' : $i; ?></span>
                        <span class="lbl"><?php echo $meta['title']; ?></span>
                    </div>
                    <?php if ($i < $totalSteps): ?>
                        <span class="bar <?php echo $i < $currentStep ? 'filled' : ''; ?>"></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($_SESSION['alert'])): $a = $_SESSION['alert']; ?>
            <div class="alert <?php echo $a['type'] === 'success' ? 'success' : 'error'; ?>">
                <span class="ic"><?php echo $a['type'] === 'success' ? '✓' : '!'; ?></span>
                <span><?php echo $a['message']; ?></span>
            </div>
            <?php unset($_SESSION['alert']); ?>
        <?php endif; ?>

        <?php if ($currentStep === 5): ?>
            <div class="center">
                <div class="done-mark">
                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
                </div>
                <div class="kicker">All done</div>
                <h1 style="font-family:var(--font-display);font-weight:700;font-size:26px;letter-spacing:-.02em;margin:6px 0 6px;">Installation complete</h1>
                <p style="color:var(--muted);font-size:14px;margin:0;">Your Jannah VTU instance is ready to go.</p>
                <div class="note">For security, delete the <code>public/install</code> folder from your server.</div>
                <a href="/admin/login" class="btn" style="margin-top:20px;text-decoration:none;">Go to admin login &rarr;</a>
            </div>
        <?php elseif (isset($steps[$currentStep])): $meta = $steps[$currentStep]; ?>
            <div class="head">
                <div class="kicker">Step <?php echo $currentStep; ?> of <?php echo $totalSteps; ?></div>
                <h1><?php echo $meta['title']; ?></h1>
                <p><?php echo $meta['desc']; ?></p>
            </div>

            <form method="POST">
                <input type="hidden" name="step" value="<?php echo $currentStep; ?>">
                <?php if (!empty($meta['hidden'])): foreach ($meta['hidden'] as $name => $value): ?>
                    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($value); ?>">
                <?php endforeach; endif; ?>

                <?php foreach ($meta['fields'] as $name => $label): ?>
                    <div class="field">
                        <label for="f_<?php echo $name; ?>"><?php echo $label; ?></label>
                        <div class="ctrl">
                            <input id="f_<?php echo $name; ?>"
                                   type="<?php echo (str_contains($name, 'pass') || $name === 'password') ? 'password' : ($name === 'email' || $name === 'mail_from' ? 'email' : ($name === 'app_url' ? 'url' : 'text')); ?>"
                                   name="<?php echo $name; ?>"
                                   placeholder="<?php echo $placeholders[$name] ?? ''; ?>"
                                   <?php echo $name === 'mail_port' ? 'inputmode="numeric"' : ''; ?>
                                   required>
                        </div>
                    </div>
                <?php endforeach; ?>

                <button type="submit" class="btn">
                    <?php echo $currentStep === $totalSteps ? 'Finish installation' : 'Continue'; ?>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                </button>
            </form>
        <?php endif; ?>
    </div>

    <div class="foot">Jannah VTU &middot; guided installer</div>
</div>
</body>
</html>
