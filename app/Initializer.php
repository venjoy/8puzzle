<?php 

namespace App;

class Initializer 
{
    protected $data;
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function fromSize()
    {
        $this->data[0][0]=2;
        $this->data[0][1]=3;
        $this->data[0][2]=5;
        $this->data[1][0]=7;
        $this->data[1][1]=6;
        $this->data[1][2]=4;
        $this->data[2][0]=1;
        $this->data[2][2]=8;
        $this->data[2][1]=NULL;
        
        $this->game->setData($this->data);
    }

    public function fromPost($post)
    {
        $this->initializeDataFromPost($post);    
        var_dump($this->data);
        die();


        $this->game->setData($this->data);
    }

    public function initializeDataFromPost($post)
    {
        foreach ($post as $key => $val)
        {
            $exp = explode('-', $key);
            // check btn-1-1 
            if (count($exp) !== 2) continue;
            $row = $exp[0];
            $col = $exp[1];
            $this->data[$row][$col] = $val;
        }
        var_dump($this->data);
        die();
    }

}