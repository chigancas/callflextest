<?php

namespace src\core;

use src\core\Database;

class App
{

    protected $controller = 'Dashboard';
    protected $method = 'index';
    protected $page404 = false;
    protected $params = [];

    public function __construct()
    {
        $URL_ARRAY = $this->parseUrl();

        $db = new Database;

        $db->generateDb();

        session_start();

        $this->getControllerFromUrl($URL_ARRAY);
        $this->getMethodFromUrl($URL_ARRAY);
        $this->getParamsFromUrl($URL_ARRAY);

        call_user_func_array([$this->controller, $this->method], $this->params);
    }

    private function parseUrl()
    {
        $REQUEST_URI = explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1));

        return $REQUEST_URI;
    }

    private function getControllerFromUrl($url)
    {

        if ( !empty($url[1]) && isset($url[1]) ) {

            if ( file_exists('src/controllers/' . ucfirst($url[1])  . '.php') ) {
                $this->controller = ucfirst($url[1]);
            } else {
                $this->page404 = true;
                $this->method = 'pageNotFound';
            }
        }

        if(!isset($_SESSION["user_id"]) && $url[1] !== "API"):
            $this->controller = 'Login';    
        endif;

        require 'src/controllers/' . $this->controller . '.php';

        $this->controller = new $this->controller();

    }

    private function getMethodFromUrl($url)
    {

        if ( !empty($url[2]) && isset($url[2]) ) {

            if ( method_exists($this->controller, $url[2]) && !$this->page404) {

                $this->method = $url[2];

                if(!isset($_SESSION["user_id"]) && $url[1] !== "API"):
                    $this->method = 'index';     
                endif;

            } else {

                $this->method = 'pageNotFound';

            }

        }
    }

    private function getParamsFromUrl($url)
    {
        if (count($url) > 3) {

            $this->params = array_slice($url, 3);

        }
    }

}


?>