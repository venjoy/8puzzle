<?php 

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    include 'src/' . $class . '.php';
});

$app = new App;
$connector = new Database\Connector;