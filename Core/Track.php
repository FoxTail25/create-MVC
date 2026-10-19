<?php

namespace Core;

/**
 * Класс Track служит для передачи данных от роутера к диспетчеру.
 * Хранит имя контроллера, экшен и массив извлеченных из URI параметров.
 */
class Track
{
    private string $controller; // Имя контроллера, который обрабатывает путь
    private string $action;     // Имя метода контроллера, который обрабатывает путь
    private array $params;      // Именованный массив с параметрами запроса

    public function __construct(string $controller, string $action, array $params = [])
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params; // По умолчанию передаем пустой массив [], если параметров нет
    }

    /**
     * Магический метод для безопасного чтения приватных свойств (Read-Only)
     */
    public function __get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new \Exception("Свойство '{$property}' не существует в классе " . __CLASS__);
    }
}
