<?php

namespace Core;

use Exception;
use PDO;
use PDOException;



/*
Класс для работы с базой данных через расширение PDO. С использование подготовленных выражений. 
В классе реализованы функции Создания, Чтения, Изменения и удаления (CRUD) записей из таблицы базы данных.
В качестве ответа, возвращается именованный массив. 
В ключе success находится результат обращения к базе данных. 
В Функциях create, update и delete в ключе msg будет находится ответ в виде строки с сообщением.
В Функции read, в ключе msg будет находится массив с результатом чтения данных из БазыДанных.
*/

class Model
{
    private static PDO|null $pdo = null;
    private static string $dsn;
    private static array $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Включает режим исключений
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Устанавливает корректный режим выборки данных по умолчанию
        PDO::ATTR_EMULATE_PREPARES => false, // Отключает эмуляцию подготовленных выражений для реальной защиты MySQL
    ];
    private static string $connectTest = 'none';

    public function __construct()
    {
        // если объекта $pdo ещё не создано, то он создаётся
        if (!self::$pdo) {
            // подключаем переменные для коннекта
            require_once $_SERVER['DOCUMENT_ROOT'] . '/project/config/connection.php';
            self::$dsn = "mysql:host=" . DB_HOST . ";" . " dbname=" . DB_NAME . ";" . " charset=utf8";
            try {
                self::$pdo = new PDO(self::$dsn, DB_USER, DB_PASS, self::$opt);
                self::$connectTest = 'База данных подключена';
            } catch (PDOException $e) {
                // надо доработать логирование
                // die("Ошибка подключения к БД: " . $e->getMessage());
                self::$connectTest = 'Возникла ошибка при подключении: . $e->getMessage()';
            }
        }
    }
    // функция проверки коннект к базе данных
    public function __toString()
    {
        return self::$connectTest;
    }

    /* функция создания записи в таблице базе данных
    функция принимает в качестве параметров строку запроса типа: 'INSERT INTO users (name, age) VALUES (:name, :age)"
    Именованный массив типа: ['name' => 'alex', 'age' => 21;] Ключами в таком массиве будут имена переменных из SQL-запроса. 
    */
    protected function create(string $sqlQueryString, array $variablesArray = [])
    {

        try {
            $res = $this->prepareQuery($sqlQueryString, $variablesArray);
            $res->execute();
            $id = self::$pdo->lastInsertId();
            return ['success' => true, 'msg' => "Запись успешно создана с ID: " . $id]; // при успешном добавлении записи - в ключе msg возвращается id добавленной записи
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()]; // при ошибке - в ключе msg возвращается сообщение об ошике. 
        }
    }

    /* функция чтения записи в базе данных
    функция принимает в качестве параметров строку запроса типа: 'SELECT * FROM users WHERE name=:name or age=:age'
    Именованный массив типа: ['name' => 'alex', 'age' => 25;] Ключами в таком массиве будут имена переменных из SQL-запроса. 
    */
    protected function read(string $sqlQueryString, array $variablesArray = [])
    {
        try {
            $res = $this->prepareQuery($sqlQueryString, $variablesArray);
            $res->execute();
            return ['success' => true, 'msg' => $res->fetchAll()];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /* функция изменения записи в базе данных
    функция принимает в качестве параметров строку запроса типа: 'UPDATE users SET name = :name, age = :age WHERE id = :id'
    Именованный массив типа: ['id' => 1, 'name' => 'alex', 'age' => 25;] Ключами в таком массиве будут имена переменных из SQL-запроса. 
    */
    protected function update(string $sqlQueryString, array $variablesArray = [])
    {
        try {
            $res = $this->prepareQuery($sqlQueryString, $variablesArray);
            $res->execute();
            $msg = $res->rowCount() > 0
                ? "Данные успешно обновлены!"
                : "Строка не найдена, либо вы отправили те же самые данные, что уже были в базе.";

            return ['success' => true, 'msg' => $msg];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /* функция удаления записи в базе данных
    функция принимает в качестве параметров строку запроса типа: 'DELETE FROM users WHERE id = :id'
    Именованный массив типа: ['id' => 10] Ключами в таком массиве будут имена переменных из SQL-запроса. 
    */
    protected function delete(string $sqlQueryString, array $variablesArray = [])
    {

        try {
            $res = $this->prepareQuery($sqlQueryString, $variablesArray);
            $res->execute();

            $msg = $res->rowCount() > 0
                ? "Пользователь успешно удален."
                : "Пользователь не найден, ничего не удалено.";

            return ['success' => true, 'msg' => $msg];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }


    //Функция автоматического привязывания и типизированных данных
    private function prepareQuery(string $sqlQueryString, array $variablesArray)
    {
        // Защита от вызова методов при упавшем подключении
        if (!self::$pdo) {
            throw new Exception("Нет подключения к БД. Статус: " . self::$connectTest);
        }
        $res = self::$pdo->prepare($sqlQueryString);
        foreach ($variablesArray as $varName => $varValue) {
            if (is_int($varValue)) {
                $res->bindValue($varName, $varValue, PDO::PARAM_INT);
            } elseif (is_bool($varValue)) {
                $res->bindValue($varName, $varValue, PDO::PARAM_BOOL);
            } elseif (is_null($varValue)) {
                $res->bindValue($varName, $varValue, PDO::PARAM_NULL);
            } else {
                $res->bindValue($varName, (string)$varValue, PDO::PARAM_STR);
            }
        }
        return $res;
    }
}
