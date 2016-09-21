<?php

include_once __DIR__ . '/vendor/autoload.php';
include_once __DIR__ . '/src/constants.php';

spl_autoload_register(function ($classname) {
    require (__DIR__ . '/src/classes/' . $classname . '.php');
});

include_once __DIR__ . '/src/config.php';
include_once __DIR__ . '/src/application.php';
include_once __DIR__ . '/src/routes.php';
include_once __DIR__ . '/src/dependencies.php';

Application::setup();
ApplicationRoutes::setup();
Dependencies::setup();
Application::run();
