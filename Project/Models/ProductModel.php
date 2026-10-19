<?php

namespace Project\Models;

use Core\Model;

class ProductModel extends Model
{
    // Указываем имя таблицы в SQLite, с которой будет работать ORM
    protected string $table = 'products';
}
