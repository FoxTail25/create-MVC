<?php

namespace Core;

// вовод на экран всех ошибок
error_reporting(E_ALL);
ini_set('display_errors', 'on');

// функция автозагрузки классов
spl_autoload_register();

// Записываем массив из файла с роутами в переменную:
$routes = require $_SERVER['DOCUMENT_ROOT'] . '/project/config/routes.php';

/*
Создаём экземпляр роутера и передаём в роутер роуты которые мы обрабатываем и адрес запроса пользователеля. 
В ответ получаем экземпляр класса Track 
*/
$track  = (new Router())->getTrack($routes, $_SERVER['REQUEST_URI']);

/* 
Создаём экзепляр диспатчера, вызываем его метод getPage и передаём в него экземпляр класса Track
*/
$page  = (new Dispatcher())->getPage($track);

/*
Возвращаем пользователю готовую страницу.
*/
echo (new View)->render($page);
