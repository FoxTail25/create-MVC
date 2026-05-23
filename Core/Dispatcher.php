<?php

namespace Core;
/*
Класс Диспатчер, получает экземпляр класса Track в котором содержатси имя и метод контроллера, а так же именнованный массив с именами переменных и их содержимым. 
Далее создаётся новый экземпляр контроллера. У него проверяется наличие нужного метода. 
Если метод существует, он вызывается и в него передаётся именованнай массив с данными. 
Если метод созданного контроллера возвращает ответ, то он передаётся в созданный экземпляр класса Page...
*/

class Dispatcher
{
    public function getPage(Track $track)
    {
        $className = ucfirst($track->controller) . 'Controller';
        $fullName = "\\Project\\Controllers\\$className";

        try {
            $controller = new $fullName;

            if (method_exists($controller, $track->action)) {
                $result = $controller->{$track->action}($track->params);

                if ($result) {
                    return $result;
                } else {
                    return new Page('default');
                }
            } else {
                echo "Method <b>{$track->action}</b> not found in class $fullName.";
                die();
            }
        } catch (\Exception $error) {
            echo $error->getMessage();
            die();
        }
    }
}
