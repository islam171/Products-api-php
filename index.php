<?php

    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
    header('Access-Control-Allow-Headers: Content-Type, X-Auth-Token, Origin, Authorization');

    spl_autoload_register(function ($class) {
        $path = str_replace('\\', '/', $class . '.php');
        if (file_exists($path)) {
            require $path;
        }
    });
    require_once __DIR__ . "./app/routes/routes.php";

