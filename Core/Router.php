<?php

namespace Core;

class Router
{
    /**
     * Проверяет совпадение маршрута пользователя с имеющимися роутами.
     * Возвращает экземпляр класса Track или выбрасывает 404 ошибку.
     */
    public function getTrack(array $routes, string $uri): Track
    {
        // Нормализуем URI: убираем лишний слэш в конце для единообразия (кроме главной страницы '/')
        $uri = ($uri !== '/') ? rtrim($uri, '/') : $uri;

        foreach ($routes as $route) {
            // Формируем регулярное выражение из пути роута
            $pattern = $this->createPattern($route->path);

            // Если совпадение найдено
            if (preg_match($pattern, $uri, $matches)) {
                // Очищаем параметры от цифровых ключей preg_match
                $params = $this->clearParams($matches);
                
                return new Track($route->controller, $route->action, $params);
            }
        }

        // Если ни один роут не подошел
        throw new \Exception("Страница по адресу '{$uri}' не найдена.", 404);
    }

    /**
     * Создает регулярное выражение из человекопонятного пути (например, /test/:id)
     */
    private function createPattern(string $path): string
    {
        // Тоже убираем слэш на конце у правила роута, если он там есть
        $path = ($path !== '/') ? rtrim($path, '/') : $path;
        
        // Превращаем /:var в именованный карман регулярного выражения
        return '#^' . preg_replace('#/:([^/]+)#', '/(?<$1>[^/]+)', $path) . '$#';
    }

    /**
     * Фильтрует массив совпадений, оставляя только строковые (именованные) ключи
     */
    private function clearParams(array $matches): array
    {
        return array_filter($matches, function($key) {
            return !is_int($key);
        }, ARRAY_FILTER_USE_KEY);
    }
}
