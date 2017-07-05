<?php 

function dump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function dd($var) {
    dump($var);
    die();
}

function app($get = null) 
{
    static $container;

    if (! $container) $container = new Venjoy\Pedy\Container;

    return $get ? $container->get($get) : $container;
}

function findBlankPos($data, $side)
{
    for ($row=0; $row < $side; $row++)
    {
        for ($col=0; $col < $side; $col++)
        {
            if ($data[$row][$col] == NULL)
            {
                $blankRow = $row;
                $blankCol = $col;
            }
        }
    }

    return array($blankRow, $blankCol);
}

function adjacent($pos1, $pos2)
{
    $x = $pos1[0];
    $y = $pos1[1];
    $a = $pos2[0];
    $b = $pos2[1];

    return (($a == $x && $b == $y-1) || ($a == $x && $b == $y+1) || ($a == $x-1 && $b == $y) || ($a == $x+1 && $b == $y)) ? 1 : 0;
}

function swapData($data, $pos1, $pos2)
{
    $temp = $data[$pos2[0]][$pos2[1]];
    $data[$pos2[0]][$pos2[1]] = $data[$pos1[0]][$pos1[1]];
    $data[$pos1[0]][$pos1[1]] = $temp;

    return $data;
}