<?php

class ApplicationConfig {

    public static function getConfig() {
        return [
            'app' => [
                'settings' => [
                    'displayErrorDetails' => true,
                    'addContentLengthHeader' => false,
                    'db' => [
                        'host' => 'localhost',
                        'user' => 'root',
                        'pass' => 'root',
                        'dbname' => 'php-slim'
                    ]
                ]
            ],
            'paths' => [
                'logger' => [
                    'main' => __DIR__ . '/../../../../logs/app.log'
                ],
                'templates' => __DIR__ . '/templates/'
            ]
        ];
    }
}
