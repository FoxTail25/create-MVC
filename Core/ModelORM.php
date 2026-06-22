<?php

namespace Core;

use PDO;
use PDOException;
use PDOStatement;
use Exception;

abstract class Model
{
    protected static ?PDO $pdo = null;

    // Каждая дочерняя модель (например, User) укажет здесь свое имя таблицы
    protected string $table = '';

    private static array $opt = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    private static string $connectTest = 'none';

    public function __construct()
    {
        if (!self::$pdo) {
            try {
                self::$pdo = new PDO('sqlite:Project/database/database.sqlite', null, null, self::$opt);
                self::$connectTest = 'База данных подключена';
            } catch (PDOException $e) {
                self::$connectTest = 'Возникла ошибка при подключении: ' . $e->getMessage();
            }
        }
    }

    /**
     * Автоматическое создание записи
     * Принимает только массив данных: ['name' => 'Alex', 'age' => 25]
     */
    public function create(array $data)
    {
        try {
            $fields = array_keys($data);
            // Формируем плейсхолдеры: :name, :age
            $placeholders = array_map(fn($field) => ":$field", $fields);

            // Собираем SQL: INSERT INTO users (name, age) VALUES (:name, :age)
            $sql = "INSERT INTO `{$this->table}` (" . implode(', ', $fields) . ") VALUES (" . implode(', ', $placeholders) . ")";

            $res = $this->prepareQuery($sql, $data);
            $res->execute();

            return ['success' => true, 'id' => self::$pdo->lastInsertId()];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * Автоматический поиск записей по условию
     * Принимает массив условий для WHERE: ['age' => 25, 'status' => 'active']
     * Если массив пустой — выберет все записи
     */
    public function read(array $where = [])
    {
        try {
            $sql = "SELECT * FROM `{$this->table}`";

            if (!empty($where)) {
                $conditions = [];
                foreach (array_keys($where) as $field) {
                    $conditions[] = "`$field` = :$field";
                }
                // Собираем SQL: SELECT * FROM users WHERE `age` = :age AND `status` = :status
                $sql .= " WHERE " . implode(' AND ', $conditions);
            }

            $res = $this->prepareQuery($sql, $where);
            $res->execute();

            return ['success' => true, 'data' => $res->fetchAll()];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * Автоматическое обновление по ID
     * $id — какую строку обновляем
     * $data — новые данные: ['email' => 'new@mail.com']
     */
    public function update(int $id, array $data)
    {
        try {
            $fields = [];
            foreach (array_keys($data) as $field) {
                $fields[] = "`$field` = :$field";
            }

            // Собираем SQL: UPDATE users SET `email` = :email WHERE id = :id
            $sql = "UPDATE `{$this->table}` SET " . implode(', ', $fields) . " WHERE `id` = :primary_id_val";

            // Добавляем ID в массив для привязки, используя уникальный ключ, чтобы не пересекаться с полями $data
            $data['primary_id_val'] = $id;

            $res = $this->prepareQuery($sql, $data);
            $res->execute();

            return ['success' => true, 'count' => $res->rowCount()];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    /**
     * Автоматическое удаление по ID
     */
    public function delete(int $id)
    {
        try {
            $sql = "DELETE FROM `{$this->table}` WHERE `id` = :id";

            $res = $this->prepareQuery($sql, ['id' => $id]);
            $res->execute();

            return ['success' => true, 'count' => $res->rowCount()];
        } catch (Exception $e) {
            return ['success' => false, 'msg' => $e->getMessage()];
        }
    }

    private function prepareQuery(string $sqlQueryString, array $variablesArray): PDOStatement
    {
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
/*
Как теперь создавать классы таблиц (Модели)Теперь создание конкретной модели занимает всего несколько строк. Класс просто наследует Model и указывает имя своей таблицы.Создаем файл User.php:php

<?php

namespace Models;

use Core\Model;

class User extends Model
{
    // Указываем, с какой таблицей работает эта модель
    protected string $table = 'users';
}



Теперь работа с базой данных выглядит максимально чистой. Никакого SQL-кода во внешних файлах:php

<?php

use Models\User;

$userModel = new User();

// 1. Создание пользователя
$newUser = $userModel->create([
    'name' => 'Владимир',
    'age' => 30,
    'email' => 'vladimir@example.com'
]);

// 2. Поиск пользователей, которым 30 лет
$users = $userModel->read(['age' => 30]);
print_r($users['data']);

// 3. Обновление пользователя с ID 1
$userModel->update(1, ['name' => 'Владимир Обновленный']);

// 4. Удаление пользователя с ID 2
$userModel->delete(2);
*/