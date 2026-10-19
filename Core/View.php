<?php

namespace Core;

class View
{
    /**
     * Формирует финальный HTML-код страницы, объединяя Layout и View.
     */
    public function render(Page $page): string
    {
        return $this->renderLayout($page, $this->renderView($page));
    }

    /**
     * Вставляет готовый контент представления внутрь главного шаблона (Layout).
     */
    private function renderLayout(Page $page, string $content): string
    {
        // Используем константу ROOT и соблюдаем правильный регистр папок (Project/Layouts)
        $layoutPath = ROOT . "/Project/Layouts/{$page->layout}.php";

        if (!file_exists($layoutPath)) {
            throw new \Exception("Файл шаблона (Layout) не найден по пути: $layoutPath", 500);
        }

        ob_start();
        $title = $page->title; // Переменная $title становится доступна внутри layout.php
        include $layoutPath;
        return ob_get_clean();
    }

    /**
     * Рендерит внутреннее представление (View) страницы и наполняет его данными.
     */
    private function renderView(Page $page): string
    {
        if (!$page->view) {
            return '';
        }

        // Соблюдаем правильный регистр папок (Project/Views)
        $viewPath = ROOT . "/Project/Views/{$page->view}.php";

        if (!file_exists($viewPath)) {
            throw new \Exception("Файл отображения (View) не найден по пути: $viewPath", 500);
        }

        ob_start();
        extract($page->data); // Превращает ['products' => $list] в переменную $products внутри view.php
        include $viewPath;
        return ob_get_clean();
    }
}
