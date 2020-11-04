<?php
use Core\Application;
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);



define('ROOT_DIR', realpath(__DIR__));

include ROOT_DIR . DIRECTORY_SEPARATOR . 'config.php';
require ROOT_DIR . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

try {
    $app = new Application();
    echo $app->run();
} catch (\Exception $exception) {
    if(DEV_MOD) {
        echo $exception->getMessage();
        exit();
    }

    header('HTTP/1.1 500 Internal Server Error');
    exit();
}


