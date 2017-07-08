<?php

namespace App\Contracts;

interface Movement
{
    public function findNextMove($wrongNum);
}