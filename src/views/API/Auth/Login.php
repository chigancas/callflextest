<?php

use src\core\Authentication;

$dados = explode("&", $data["dados"]);
$values = [];

foreach($dados as $key=>$value):
    $dados[$key] = explode("=", $value);
    array_push($values,$dados[$key][1]);
endforeach;

$auth = new Authentication;

$result = $auth->getUser($values[0],$values[1]);

echo $result;

?>