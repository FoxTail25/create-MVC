<?php

namespace Core;

/*
Класс Траск служит для получения от роутера "имени контроллера", "действия контроллера", (которые будут обрабатывать маршрут) и "именованного массива" с именам переменных и их содержимым (которые будут отправлены в контроллер для дальнейшей обработки).
*/

class Track
{
    private string $controller; // Имя контроллера который обрабатывает путь
    private string $action; // Имя метода контроллера который обрабатывает путь
    private array $params; // Именованный массив, передаваемый в представление. В котором в качестве ключа указывается имя переменной, а в качестве свойства содержание этой переменной.

    public function __construct(string $controller, string $action, array $params = null)
    {
        $this->controller = $controller;
        $this->action = $action;
        $this->params = $params;
    }

    public function __get(string $property)
    {
        return $this->$property;
    }
}
