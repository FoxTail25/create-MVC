<?php

namespace Core;

/**
 * Базовый класс Контроллер формирует данные для ответа на запрос пользователя.
 */
abstract class Controller
{
    // Значения по умолчанию для шаблона (layout) и заголовка (title)
    protected string $layout = 'default';
    protected string $defaultTitle = 'MVC-framework';

    /**
     * Вспомогательный метод для быстрой генерации объекта страницы.
     * Передаем заголовок, имя вьюшки и данные одной строкой.
     */
    protected function render(string $title, string $view = '', array $data = []): Page
    {
        // Если заголовок не передан, используем дефолтный
        $pageTitle = !empty($title) ? $title : $this->defaultTitle;
        
        return new Page($this->layout, $pageTitle, $view, $data);
    }
}
