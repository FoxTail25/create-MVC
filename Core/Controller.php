<?php

namespace Core;

class Controller
{
    protected string $layout = 'default';
    protected string $title = 'MVC-framework';

    protected function render(string $view, array $data)
    {
        return new Page($this->layout, $this->title, $view, $data);
    }
}
