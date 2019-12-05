<?php

namespace src\core;

class Controller
{

    public function model($model)
    {
        require 'src/models/' . $model . '.php';

        $classe = 'src\\models\\' . $model;

        return new $classe();
    }

    public function view(string $view, $data = [])
    {
        require 'src/views/' . $view . '.php';
    }

    public function pageNotFound()
    {
        $this->view('erro404');
    }

}

?>