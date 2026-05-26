<?php

namespace Project\Models;

use Core\Model;

class TestModel extends Model
{
    public function allData()
    {
        $sql = 'SELECT * FROM product';
        $res = $this->read($sql);
        return $res;
    }
    public function getProduct()
    {
        // $sql = 'SELECT * FROM product WHERE id=:id';
        $sql = 'SELECT * FROM product WHERE name=:name';
        $varArr = [
            // 'id' => 3,
            'name' => 'Спички'
        ];
        $res = $this->read($sql, $varArr);
        return $res;
    }
}
