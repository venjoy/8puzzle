<?php

namespace App\AI;

class Path {
    public $isTraversed = false;

    public $route = [];

    public function __construct($initPos)
    {
        $this->route[] = $initPos; 
    }
}
