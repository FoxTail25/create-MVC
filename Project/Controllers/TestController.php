<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\TestModel;

class TestController extends Controller
{
    function modelTest()
    {
        // $res = (new TestModel())->allData();
        $res = (new TestModel())->getProduct();
        var_dump($res);
    }
}
