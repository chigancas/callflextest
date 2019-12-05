<?php

use src\core\Database;

$dados = explode("&", $data["dados"]);
$columnsOld = [];
$valuesOld = [];
$columns = [];
$values = [];

foreach($dados as $key=>$value):
    $dados[$key] = explode("=", $value);
    
    if(in_array("descriptionOld", $dados[$key]) || in_array("codeOld", $dados[$key]))
    {

        array_push($valuesOld,$dados[$key][1]);

    }else
    {

        array_push($columnsOld,$dados[$key][0]);
        array_push($columns,$dados[$key][0]);
        array_push($values,$dados[$key][1]);

    }

endforeach;

$db = new Database;

$result = $db->update("products",
array(
    "column" => $columns,
    "value" => $values
),
array(
    "column" => $columnsOld,
    "value" => $valuesOld
)
);

echo "OK";

?>