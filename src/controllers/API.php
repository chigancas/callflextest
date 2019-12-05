<?php

use src\core\Controller;

class API extends Controller
{

  public function Produtos($data)
  {

    $data = explode("?", urldecode($data));
    
    $this->view('API/Produtos/'.$data[0], ["dados" => $data[1]]);

  }

  public function Auth($data)
  {

    $data = explode("?", urldecode($data));
    
    $this->view('API/Auth/'.$data[0], ["dados" => $data[1]]);

  }
  
}


?>