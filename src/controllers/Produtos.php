<?php

use src\core\Controller;

class Produtos extends Controller
{

  public function index()
  {
    header("Location: produtos/page/1");
  }

  public function page($pg = null)
  {
    if (is_numeric($pg)) {
      $produtos = $this->model("Produtos");
      $data = json_decode($produtos::findAll());
      if($pg > round($data->total / 10)):
        $this->pageNotFound();
        die;
      endif;
      $data2 = json_decode($produtos::findByPage($pg));
      $this->view('produtos/page', ["total" => $data, "produtos" => $data2]);
    } else {
      $this->pageNotFound();
    }
  }
  
}


?>