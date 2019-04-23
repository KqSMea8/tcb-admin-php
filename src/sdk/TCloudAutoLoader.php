<?php
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'GuzzleHttp' . DIRECTORY_SEPARATOR . 'guzzle' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'GuzzleHttp' . DIRECTORY_SEPARATOR . 'Psr7' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions.php');
require_once(__DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'GuzzleHttp' . DIRECTORY_SEPARATOR . 'Promises' . DIRECTORY_SEPARATOR . 'src' . DIRECTORY_SEPARATOR . 'functions.php');
require __DIR__ . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'autoload.php';

spl_autoload_register(function ($class_name) {

    // echo '1' . $class_name . PHP_EOL;
    $clsName = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $clsName . '.php')) {
        // echo __DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $clsName . '.php' . PHP_EOL;
        require_once(__DIR__ . DIRECTORY_SEPARATOR . "src" . DIRECTORY_SEPARATOR . $clsName . '.php');
    }
});

spl_autoload_register(function ($class_name) {
    // echo '2' . $class_name . PHP_EOL;
    $clsName = str_replace("\\", DIRECTORY_SEPARATOR, $class_name);
    if (is_file(__DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . $clsName . '.php')) {
        // echo __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . $clsName . '.php' . PHP_EOL;
        require_once(__DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . $clsName . '.php');
    }
});
?>