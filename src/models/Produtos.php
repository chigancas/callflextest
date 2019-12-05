<?php

namespace src\models;

use src\core\Database;

class Produtos
{

    public static function findAll()
    {
        $db =  new Database;

        return $db->select("products",0,["*"]);
    }

    public static function findByPage(int $page)
    {
        $db =  new Database;

        return $db->select("products",$page * 10,["*"]);
    }


}

?>