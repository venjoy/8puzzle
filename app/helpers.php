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

function findposzero()
{
for($row=0;$row<$this->getside();$row++)
    {
        for($col=0;$col<$this->getside();$col++)
        {
            if($this->data[$row][$col]==NULL)
            {
                $x=$row;
                $y=$col;
            }
        }
    }
    return array($x, $y);
}