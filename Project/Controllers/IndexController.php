<?php

namespace Project\Controllers;

use Core\Controller;

class IndexController extends Controller
{
    public function home()
    {
        return $this->render('Главная страница', 'index/home');
    }
}
