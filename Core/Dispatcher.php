<?php

namespace Core;

class Dispatcher
{
    /**
     * Динамически создает контроллер и вызывает необходимый метод (экшен).
     * Возвращает экземпляр класса Page или выбрасывает исключение.
     */
    public function getPage(Track $track): Page
    {
        // Приводим первую букву к верхнему регистру (например, product -> ProductController)
        $className = ucfirst($track->controller) . 'Controller';
        $fullName = "\\Project\\Controllers\\$className";

        // 1. Проверяем, существует ли вообще класс такого контроллера
        if (!class_exists($fullName)) {
            throw new \Exception("Контроллер '{$fullName}' не найден.", 404);
        }

        $controller = new $fullName();

        // 2. Проверяем, есть ли у контроллера запрашиваемый метод
        if (!method_exists($controller, $track->action)) {
            throw new \Exception("Метод '{$track->action}' не найден в классе {$fullName}.", 404);
        }

        // 3. Вызываем метод с автоматической подстановкой параметров
        $result = $this->invokeAction($controller, $track->action, $track->params);

        // 4. Гарантируем возврат объекта Page
        if ($result instanceof Page) {
            return $result;
        }

        return new Page('default');
    }

    /**
     * Магия Reflection: сопоставляет имена параметров в методе контроллера 
     * с именами ключей из массива параметров роутера.
     */
    private function invokeAction(object $controller, string $action, array $params)
    {
        $reflection = new \ReflectionMethod($controller, $action);
        $arguments = [];

        // Перебираем все параметры, которые ожидает метод в контроллере
        foreach ($reflection->getParameters() as $param) {
            $name = $param->getName();

            // Если в роутере есть параметр с таким же именем — берем его
            if (array_key_exists($name, $params)) {
                $arguments[] = $params[$name];
            } elseif ($param->isDefaultValueAvailable()) {
                // Если параметра нет, но у него в контроллере есть дефолтное значение (например, $id = null)
                $arguments[] = $param->getDefaultValue();
            } else {
                // Если параметр обязателен, а роутер его не передал
                throw new \Exception("Отсутствует обязательный параметр '\${$name}' для метода {$action}.", 500);
            }
        }

        // Вызываем метод контроллера и передаем аргументы как отдельные переменные
        return $reflection->invokeArgs($controller, $arguments);
    }
}
