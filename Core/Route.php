<?php

namespace Core;

class Route
{
    private string $path;
    private string $controller;
    private string $action;

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
