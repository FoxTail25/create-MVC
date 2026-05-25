<?php

namespace Core;

class Controller
{
    public string $title = 'MVC-framework';
    public string $layout = 'default';

    protected function render(string $view, array $data = [])
    {
        return new Page($this->layout, $this->title, $view, $data);
    }
}
