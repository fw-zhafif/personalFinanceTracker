<?php

// Memastikan direktori sementara untuk cache tampilan Blade tersedia pada filesystem serverless
if (!is_dir('/tmp/views')) {
    mkdir('/tmp/views', 0755, true);
}

require __DIR__ . '/../public/index.php';