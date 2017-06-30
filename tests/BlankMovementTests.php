<?php

spl_autoload_register(function ($class) {
    $class = str_replace('\\', '/', $class);
    $class = str_replace('App', 'app', $class);
    include '../' . $class . '.php';
});

$blankMovement = new App\Movements\BlankMovement;

$blankMovement->sayHello();