<?php

namespace Core;

use PDO;
use PDOException;

class Model
{
    private static PDO|null $pdo = null;
    private static string $dsn;
    private static array $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];
    private static string $connectTest = 'none';

    public function __construct()
    {
        if (!self::$pdo) { // если свойство не задано, то подключаемся
            self::$dsn = "mysql:host=" . DB_HOST . ";" . " dbname=" . DB_NAME . ";" . " charset=utf8";
            try {
                self::$pdo = new PDO(self::$dsn, DB_USER, DB_PASS, self::$opt);
                self::$connectTest = 'База данных подключена';
            } catch (PDOException $e) {
                self::$connectTest = 'Возникла ошибка при подключении: . $e->getMessage()';
            }
        }
    }
    public function __toString()
    {
        return self::$connectTest;
    }
}
