<?php

/**
 * PDO wrappper class to handle some most common database operations.
 */
class Database {

    /**
     * Gets PDO adapter.
     *
     * @return PDO
     */
    public static function getPdo() {
        return Application::getSlimAppContainer()->db;
    }

    /**
     * Returns prepared PDO statement as a result of execution of SQL query.
     *
     * @param  string $sql
     * @param  array  $args
     * @return PDOStatement
     */
    public static function execute($sql, $args = null) {
        $pdo = self::getPdo();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($args);
        return $stmt;
    }

    /**
     * Returns data after fetching all matching rows.
     *
     * @param  string $sql
     * @param  array  $args
     * @return array
     */
    public static function fetchAll($sql, $args = null) {
        return self::execute($sql, $args)->fetchAll();
    }
}
