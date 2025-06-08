<?php
// Configurações do banco de dados MySQL
class DatabaseConfig {
    public static $host = 'localhost';
    public static $dbname = 'recrutamento_api';
    public static $username = 'root';
    public static $password = '1234';

    public static function getDSN() {
        return "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8";
    }
}