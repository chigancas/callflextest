<?php

require 'src/autoload.php';
use src\core\App;
use src\core\Controller;

$REQUEST_URI = explode('/', substr(filter_input(INPUT_SERVER, 'REQUEST_URI'), 1));

if(in_array("API",$REQUEST_URI))
{

  $app = new App();

}else
{  

?>
<!DOCTYPE html>
<html lang="pt-br" class="h-100 w-100">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Simple Framework</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="/callflex/public/css/pro.min.css">
    <link rel="stylesheet" href="/callflex/public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/callflex/public/css/style.css">
    <link rel="shortcut icon" href="/callflex/public/images/favicon.png" type="image/x-icon">
  </head>
  <body class="h-100 w-100">

  <?php    
    $app = new App();
  ?>
  <script src="/callflex/public/js/jquery-3.4.1.min.js"></script>
  <script src="/callflex/public/js/main.js"></script>
  <script src="/callflex/public/js/bootstrap.bundle.min.js"></script>
  </body>
</html>

<?php

}

?>