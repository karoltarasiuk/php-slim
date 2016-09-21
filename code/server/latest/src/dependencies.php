<?php

$config = ApplicationConfig::getConfig();
$container = Application::getSlimAppContainer();

// monolog setup
$container['logger'] = function($container) {
    $logger = new \Monolog\Logger('logger');
    $file_handler = new \Monolog\Handler\StreamHandler($config['paths']['logger']['main']);
    $logger->pushHandler($file_handler);
    return $logger;
};

// mysql connection
$container['db'] = function ($container) {
    $db = $container['settings']['db'];
    $pdo = new PDO(
        'mysql:host=' . $db['host'] . ';dbname=' . $db['dbname'],
        $db['user'],
        $db['pass']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    return $pdo;
};

// templates renderer
$container['view'] = new \Slim\Views\PhpRenderer($config['paths']['templates']);
