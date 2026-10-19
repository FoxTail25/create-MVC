<?php

namespace Project\Controllers;

use Core\Controller;

class ErrorController extends Controller
{
    /**
     * Экшен для отображения 404 ошибки
     * Переменная $message автоматически прокинется из параметров трека
     */
    public function notFound($message = 'Страница не найдена')
    {
        return $this->render('Ошибка 404', 'error/notFound', [
            'message' => $message
        ]);
    }
}
