<?php

use src\core\Database;

$dados = explode("&", $data["dados"]);
$columns = [];
$values = [];

foreach($dados as $key=>$value):
    $dados[$key] = explode("=", $value);
    array_push($columns,$dados[$key][0]);
    array_push($values,$dados[$key][1]);
endforeach;

$db = new Database;

$result = $db->delete("products",
array(
    "column" => $columns,
    "value" => $values
)
);

echo "OK";

?>