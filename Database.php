<?php
class Database {
    public static function connect() {
        return new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASS);
    }
}
?>