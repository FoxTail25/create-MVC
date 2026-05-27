<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\ProductModel;

class ProductController extends Controller
{
    public function all()
    {
        $data = (new ProductModel)->all(); // Получаем данные из БД.


        if ($data['success']) {
            $this->title = 'all product';
            return $this->render('product/all', ['products' => $data['msg']]);
        } else {
            echo 'Не удалось получить данные из BD';
        }
    }
    public function product(mixed $params)
    {
        $productId = $params['id'];
        $data = (new ProductModel)->productById($productId);

        if ($data['success']) {
            if (count($data['msg']) > 0) { // Если проверка на наличие данных. Если они есть то....
                $this->title = $data['msg'][0]['name'];
                return $this->render('product/product', ['product' => $data['msg'][0]]);
            } else { // Если данных нет то....
                return $this->render('errors/noproduct');
            }
        } else {
            echo 'Не удалось получить данные из BD';
        }
    }
}
