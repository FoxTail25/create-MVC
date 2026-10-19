<?php

namespace Core;

use PDO;
use PDOException;
use PDOStatement;
use Exception;

abstract class Model
{
    protected static ?PDO $pdo = null;

    // Имя таблицы для ORM-методов (переопределяется в Project/Models)
    protected string $table = '';

    private static array $opt = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    public function __construct()
    {
        if (!self::$pdo) {
            try {
                // Если в конфигурации заданы настройки MySQL — подключаем его
                if (defined('DB_HOST') && !empty(DB_HOST) && defined('DB_NAME') && !empty(DB_NAME)) {
                    // Подключение к MySQL...
                    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4";
                    self::$pdo = new PDO($dsn, DB_USER, DB_PASS, self::$opt);
                } else {
                    // Иначе надёжно подключаем SQLite, используя константу ROOT
                    $dsn = 'sqlite:' . ROOT . '/Project/Database/database.sqlite';
                    self::$pdo = new PDO($dsn, null, null, self::$opt);
                }
            } catch (PDOException $e) {
                throw new Exception("Критическая ошибка подключения к базе данных: " . $e->getMessage(), 500);
            }
        }
    }

    // =========================================================================
    // ГРУППА 1: АВТОМАТИЧЕСКИЕ МЕТОДЫ (ORM)
    // =========================================================================

    /**
     * ORM: Создание записи по массиву данных. Возвращает ID созданной записи.
     */
    public function create(array $data): string
    {
        $fields = array_keys($data);
        $placeholders = array_map(fn($field) => ":$field", $fields);

        $sql = "INSERT INTO {$this->table} (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";
        return $this->queryCreate($sql, $data);
    }

    /**
     * ORM: Поиск записей по массиву условий WHERE. Возвращает массив результатов.
     */
    public function read(array $where = []): array
    {
        $sql = "SELECT * FROM {$this->table}";

        if (!empty($where)) {
            $conditions = [];
            foreach (array_keys($where) as $field) {
                $conditions[] = "{$field} = :{$field}";
            }
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }

        return $this->queryRead($sql, $where);
    }

    /**
     * ORM: Обновление записи по ID. Возвращает число измененных строк.
     */
    public function update(int $id, array $data): int
    {
        $fields = [];
        foreach (array_keys($data) as $field) {
            $fields[] = "{$field} = :{$field}";
        }

        $sql = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = :primary_id_val";
        $data['primary_id_val'] = $id;

        return $this->queryUpdate($sql, $data);
    }

    /**
     * ORM: Удаление записи по ID. Возвращает число удаленных строк.
     */
    public function delete(int $id): int
    {
        $sql = "DELETE FROM {$this->table} WHERE id = :id";
        return $this->queryDelete($sql, ['id' => $id]);
    }


    // =========================================================================
    // ГРУППА 2: ПРЯМЫЕ СЫРЫЕ SQL-ЗАПРОСЫ (Из ваших файлов MySQL/SQLite)
    // =========================================================================

    /**
     * SQL: Выполнить кастомный INSERT. Возвращает ID созданной записи.
     */
    protected function queryCreate(string $sqlQueryString, array $variablesArray = []): string
    {
        $res = $this->prepareQuery($sqlQueryString, $variablesArray);
        $res->execute();
        return self::$pdo->lastInsertId();
    }

    /**
     * SQL: Выполнить кастомный SELECT. Возвращает чистый массив строк.
     */
    protected function queryRead(string $sqlQueryString, array $variablesArray = []): array
    {
        $res = $this->prepareQuery($sqlQueryString, $variablesArray);
        $res->execute();
        return $res->fetchAll();
    }

    /**
     * SQL: Выполнить кастомный UPDATE. Возвращает число измененных строк.
     */
    protected function queryUpdate(string $sqlQueryString, array $variablesArray = []): int
    {
        $res = $this->prepareQuery($sqlQueryString, $variablesArray);
        $res->execute();
        return $res->rowCount();
    }

    /**
     * SQL: Выполнить кастомный DELETE. Возвращает число удаленных строк.
     */
    protected function queryDelete(string $sqlQueryString, array $variablesArray = []): int
    {
        $res = $this->prepareQuery($sqlQueryString, $variablesArray);
        $res->execute();
        return $res->rowCount();
    }


    // =========================================================================
    // ВСПOMОГАТЕЛЬНЫЕ МЕТОДЫ
    // =========================================================================

    /**
     * Автоматическое привязывание типизированных данных к подготовленному выражению
     */
    private function prepareQuery(string $sqlQueryString, array $variablesArray): PDOStatement
    {
        $res = self::$pdo->prepare($sqlQueryString);
        
        foreach ($variablesArray as $varName => $varValue) {
            if (is_int($varValue)) {
                $type = PDO::PARAM_INT;
            } elseif (is_bool($varValue)) {
                $type = PDO::PARAM_BOOL;
            } elseif (is_null($varValue)) {
                $type = PDO::PARAM_NULL;
            } else {
                $type = PDO::PARAM_STR;
                $varValue = (string)$varValue;
            }
            $res->bindValue($varName, $varValue, $type);
        }
        
        return $res;
    }
}
