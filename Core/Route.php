<?php

namespace Core;
/*
Класс Route служит для хранения маршрутов нашего фреймворка
*/

class Route
{
    private string $path; // Обрабатываемый путь
    private string $controller; // Имя контроллера который обрабатывает путь
    private string $action; // Имя метода контроллера который обрабатывает путь

    public function __construct(string $path, string $controller, string $action)
    {
        $this->path = $path;
        $this->controller = $controller;
        $this->action = $action;
    }
    public function __get(string $property)
    {
        return $this->$property;
    }
}
