<?php

namespace Core;

/**
 * Класс Page содержит данные, необходимые для формирования страницы. 
 */
class Page
{
    private string $layout;
    private string $title;
    private ?string $view; // Краткая запись для string|null
    private array $data;

    public function __construct(string $layout, string $title = '', ?string $view = null, array $data = [])
    {
        $this->layout = $layout;
        $this->title  = $title;
        $this->view   = $view;
        $this->data   = $data;
    }

    /**
     * Магический метод для безопасного чтения приватных свойств (Read-Only)
     */
    public function &__get(string $property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        throw new \Exception("Свойство '{$property}' не существует в классе " . __CLASS__);
    }
}
