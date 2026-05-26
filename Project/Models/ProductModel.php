<?php

namespace Project\Models;

use Core\Model;

class ProductModel extends Model
{
    public function all()
    {
        $sqlQuery = 'SELECT * FROM product';
        return $this->read($sqlQuery);
    }
    public function productById(string | int $id)
    {
        $sqlQuery = 'SELECT * FROM product WHERE id=:id';
        $dataArr = ['id' => $id];

        return $this->read($sqlQuery, $dataArr);
    }
}
