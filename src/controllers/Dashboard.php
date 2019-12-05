<?php

use src\core\Controller;

class Dashboard extends Controller
{

  public function index()
  {
    $this->view('dashboard/index');
  }
  
}


?>