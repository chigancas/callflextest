<?php

use src\core\Database;

$dados = explode("&", $data["dados"]);
$columns = [];
$values = [];

foreach($dados as $key=>$value):
    $dados[$key] = explode("=", $value);
endforeach;

$db = new Database;

$result = $db->query("SELECT * FROM products WHERE description like '%". $dados[0][1] ."%' OR code like '%". $dados[0][1] ."%'");

print_r($result);

?>