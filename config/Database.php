<?php

class Database {
    private static $instance = null;

    public static function getConnection() {
        if (self::$instance === null) {
            self::$instance = new PDO(
                "mysql:host=localhost;dbname=unity_care_v2;charset=utf8",
                "root",
                ""
            );
            self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }
        return self::$instance;
    }
}
