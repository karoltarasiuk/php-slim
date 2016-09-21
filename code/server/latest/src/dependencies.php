<?php

class Dependencies {

    public static function setup() {
        $config = ApplicationConfig::getConfig();
        $container = Application::getSlimAppContainer();
        self::_setupLogger($config, $container);
        self::_setupDb($config, $container);
        self::_setupView($config, $container);
    }

    protected static function _setupLogger($config, $container) {
        // monolog setup
        $container['logger'] = function($container) {
            $logger = new \Monolog\Logger('logger');
            $file_handler = new \Monolog\Handler\StreamHandler($config['paths']['logger']['main']);
            $logger->pushHandler($file_handler);
            return $logger;
        };
    }

    protected static function _setupDb($config, $container) {
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
    }

    protected static function _setupView($config, $container) {
        // templates renderer
        $container['view'] = new \Slim\Views\PhpRenderer($config['paths']['templates']);
    }
}
