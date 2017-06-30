<?php

namespace App\Contracts;

interface PathFinder
{
    public function findPaths($curBlankPos, $finBlankPos, $wrtNum);
}