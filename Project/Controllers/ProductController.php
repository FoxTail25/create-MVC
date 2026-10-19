<?php

namespace Project\Controllers;

use Core\Controller;
use Project\Models\ProductModel;

class ProductController extends Controller
{
    /**
     * Отображает каталог всех товаров
     * Роут: /product/all
     */
    public function all(): \Core\Page
    {
        $productModel = new ProductModel();
        
        // ORM-метод read() без аргументов выберет абсолютно все записи из таблицы products
        $products = $productModel->read();

        // Передаем заголовок вкладки, имя файла вьюшки и массив с переменными
        return $this->render('Каталог товаров', 'product/all', [
            'products' => $products
        ]);
    }

    /**
     * Отображает карточку конкретного товара
     * Роут: /product/:id
     */
    public function product($id): \Core\Page
    {
        $productModel = new ProductModel();
        
        // Ищем товар по ID. Диспетчер сам привел параметр из URI в переменную $id
        $result = $productModel->read(['id' => $id]);

        // Так как метод read() всегда возвращает массив массивов (даже если найдена 1 строка),
        // проверяем, нашлось ли что-то в базе данных
        if (empty($result)) {
            // Выбрасываем 404 ошибку, которую перехватит наш глобальный try...catch в index.php
            throw new \Exception("Товар с ID {$id} не найден в каталоге.", 404);
        }

        // Берем самый первый (и единственный) элемент из результатов поиска
        $product = $result[0];

        return $this->render($product['title'], 'product/view', [
            'product' => $product
        ]);
    }
}
