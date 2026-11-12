<?php

$viewsFolderPath = __DIR__ . '/../resources/views';

function deleteDirectory($dir) {
    if (!is_dir($dir)) {
        return;
    }

    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $filePath = $dir . '/' . $file;
            if (is_dir($filePath)) {
                deleteDirectory($filePath);
            } else {
                unlink($filePath);
            }
        }
    }
    rmdir($dir);
}

if (is_dir($viewsFolderPath)) {
    deleteDirectory($viewsFolderPath);
    echo "Operations successful.";
} else {
    echo "Views folder does not exist.";
}