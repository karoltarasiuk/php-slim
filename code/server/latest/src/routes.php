<?php

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

class ApplicationRoutes {

    protected static $_app = null;
    protected static $_addedRoutes = [];

    public static function setup() {
        // Define app routes
        ApplicationRoutes::addRoute('', function (Request $request, Response $response, $args) {
            return $this->view->render($response, 'index.phtml');
        });

        $helloRoute = function (Request $request, Response $response, $args) {
            return $this->view->render($response, 'hello.phtml', [
                'name' => array_key_exists('name', $args) ? $args['name'] : 'stranger'
            ]);
        };
        ApplicationRoutes::addRoute('/hello', $helloRoute);
        ApplicationRoutes::addRoute('/hello/{name}', $helloRoute);

        ApplicationRoutes::addRoute('/some/rubbish/in.json', function (Request $request, Response $response, $args) {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            $body = $newResponse->getBody();
            $body->write(json_encode([
                'some' => 'rubbish'
            ]));
            return $newResponse;
        });

        ApplicationRoutes::addRoute('/db/fetchall.json', function (Request $request, Response $response, $args) {
            $newResponse = $response->withHeader('Content-type', 'application/json');
            $body = $newResponse->getBody();
            $all = Database::fetchAll('SELECT * FROM `users` WHERE `username` LIKE "%test%"');
            $body->write(json_encode($all));
            return $newResponse;
        });
    }

    /**
     * I assume that route string is without trailing slash. This slash will be
     * added here, e.g. if you pass '/home', and the default version folder is
     * 'latest', then you will have those routes registered:
     * - /home
     * - /home/
     * - /latest/home
     * - /latest/home/
     *
     * @param string    $route
     * @param function  $callback
     */
    protected static function addRoute($route, $callback) {

        if (!self::$_app) {
            self::$_app = Application::getSlimApp();
        }

        $defaultVersion = self::_getDefaultVersionFolder();
        self::$_addedRoutes[] = [
            'route' => $route,
            'callback' => $callback
        ];

        self::$_app->get($route, $callback);
        self::$_app->get($route . '/', $callback);
        self::$_app->get('/' . $defaultVersion . $route, $callback);
        self::$_app->get('/' . $defaultVersion . $route . '/', $callback);
    }

    /**
     * Gets default version's folder, for route creation purposes.
     */
    protected static function _getDefaultVersionFolder() {
        $frontControllerConfig = FrontController::getConfig();
        return $frontControllerConfig['DEFAULT_VERSION_FOLDER'];
    }
}
