<?php

use \Slim\App as App;

class Application {

    protected $_app = null;
    protected static $_instance = null;

    protected function __construct() {
        $config = ApplicationConfig::getConfig();
        $this->_app = new App($config['app']);
    }

    public static function setup() {
        self::getInstance();
    }

    public static function getInstance() {
        if (!self::$_instance) {
            self::$_instance = new Application();
        }
        return self::$_instance;
    }

    public static function getSlimApp() {
        return self::getInstance()->_app;
    }

    public static function getSlimAppContainer() {
        return self::getSlimApp()->getContainer();
    }

    public static function run() {
        self::getSlimApp()->run();
    }
}
