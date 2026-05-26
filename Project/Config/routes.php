<?php

use Core\Route;

/*
При подключении возвращает массив с роутами.
Каждый роут состоит из "обрабатываемого пути", "контроллера" и "метода контроллера" обрабоатывающего этот путь.
*/

return [
    /*
    В качестве примера указан путь "/", контроллер "index" и метод контроллера "index" - "home"
    */
    new Route('/', 'index', 'home'),
    new Route('/product/all', 'Product', 'all'),
    new Route('/product/:id', 'Product', 'product'),
];
