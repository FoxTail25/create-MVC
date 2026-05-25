<?php
namespace Project\Controllers;
use Core\Controller;

class IndexController extends Controller
{
    public function home(){
        $this->title = "Hello MVC";
        return $this->render('Index/home', ['text' => 'test']);
    }
}