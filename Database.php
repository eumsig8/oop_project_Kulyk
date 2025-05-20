<?php
class Database {
    public static function connect() {
        return new PDO("mysql:host=".DB_host.";dbname=".DB_name, DB_user, DB_pass);
    }
}
?>