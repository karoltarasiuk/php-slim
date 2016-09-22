<?php

class Database {

    public static function getPdo() {
        return Application::getSlimAppContainer()->db;
    }
}
