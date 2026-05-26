<?php

namespace Project\Controllers;

use Core\Controller;

class ErrorController extends Controller
{
    public function notFound()
    {
        $this->layout = '404';
        return $this->render('errors/404');
    }
}
