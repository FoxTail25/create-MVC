<?php

namespace Core;

// Вывод на экран всех ошибок (для локальной разработки)
error_reporting(E_ALL);
ini_set('display_errors', '1');

// Определяем базовую директорию проекта
define('ROOT', __DIR__);

// Автозагрузчик классов (PSR-4)
spl_autoload_register(function ($class) {
    $path = ROOT . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($path)) {
        require_once $path;
    }
});

try {
    // 1. Проверяем и подключаем конфигурацию БД
    $configPath = ROOT . '/Project/Config/connection.php';
    if (!file_exists($configPath)) {
        throw new \Exception("Файл конфигурации не найден по пути: $configPath", 500);
    }
    require_once $configPath;
        
    // 2. Проверяем и загружаем роуты
    $routesPath = ROOT . '/Project/Config/routes.php';
    if (!file_exists($routesPath)) {
        throw new \Exception("Файл роутов не найден по пути: $routesPath", 500);
    }
    $routes = require $routesPath; 

    // 3. Обрабатываем URI и запускаем MVC-ядро
    $url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    $track = (new Router())->getTrack($routes, $url);
    $page  = (new Dispatcher())->getPage($track);

    // 4. Выводим результат пользователю
    echo (new View)->render($page);

} catch (\Throwable $e) {
    // Сюда прилетят ВСЕ ошибки: и от отсутствия файлов, и 404 от роутера, и падения БД
    $code = ($e->getCode() === 404) ? 404 : 500;
    http_response_code($code);

    // Вызываем системный трек ошибки без всяких die()
    $errorTrack = new Track('error', 'notFound', ['message' => $e->getMessage()]);
    
    try {
        $page = (new Dispatcher())->getPage($errorTrack);
        echo (new View)->render($page);
    } catch (\Throwable $fatalError) {
        // Запасной вариант на случай, если упал даже ErrorController
        echo "<h1>Критическая ошибка сервера ({$code})</h1>";
        echo "<p>" . $e->getMessage() . "</p>";
    }
}
