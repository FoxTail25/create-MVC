<?php

namespace Core;

class Page
{
    private string $layout;
    private string $title;
    private string|null $view;
    private array $data;

    public function __construct(string $layout, string $title = '', $view = null, array $data = [])
    {
        $this->layout = $layout;
        $this->title  = $title;
        $this->view   = $view;
        $this->data   = $data;
    }

    public function __get(string $property)
    {
        return $this->$property;
    }
}
