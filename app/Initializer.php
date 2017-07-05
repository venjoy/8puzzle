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

        $this->game->setData($this->data);

        $this->swap($post);
        
        $this->game->setData($this->data);

        if($this->game->checkWin())
        {
            echo"<html>";
            echo'<div class="game">Congrats,you win the game</div>';
        }
    }

    public function initNextMove($movement)
    {
        $this->data = swapData($this->data, $movement[0], $movement[1]);
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
    }

    public function swap($post)
    {
        $blankPos = findBlankPos($this->data, $this->game->getSide());

        foreach ($post as $key => $val)
        {
            $exp = explode('-', $key);
            if (count($exp) > 2)
            {
                $numToSwapPos[0] = $exp[1];
                $numToSwapPos[1] = $exp[2];  
                if (adjacent($blankPos, $numToSwapPos))  
                {
                    $this->data = swapData($this->data, $blankPos, $numToSwapPos);
                }
            }                
        }
    }

}