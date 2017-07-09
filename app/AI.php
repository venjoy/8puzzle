<?php 

namespace App;

use App\Strategies\GeneralMovement as GenMove;
use App\Strategies\SpecialMovement as SpecMove;


class AI 
{
    protected $game;
    protected $genMovement;
    protected $specMovement;

    public function __construct(Game $game, GenMove $genMovement, SpecMove $specMovement)
    {
        $this->game = $game;
        $this->genMovement = $genMovement;
        $this->specMovement = $specMovement;
    }

    public function nextMove()
    {
        $wrongNum = $this->findNumOnWrongPos();

        if ($this->isSpecial($wrongNum))
        {
            $movements = $this->specMovement->findNextMove($wrongNum);
        }
        else if ($this->isGeneral($wrongNum))
        {
            $movements = $this->genMovement->findNextMove($wrongNum);
        }

        return $movements;
    }

    public function isGeneral($num)
    {
        $side = $this->game->getSide();

        return (($num % $side) != 0) ? 1 : 0;
    }
    
    public function isSpecial($num)
    {
        $side = $this->game->getSide();
        
        return (($num % $side) == 0) ? 1 : 0;
    }  

    public function findNumOnWrongPos()
    {
        $num = null;

        $this->game->eachNum(function($n, $pos) use (&$num) {
            if (! $this->isNumOnRightPos($n, $pos)) {
                $num = $n;
                return false;
              
            }
        });

        
        return $num;
    }

    public function isNumOnRightPos($num, $pos)
    {
        return $num == $this->game->getSide() * $pos[0] + $pos[1] + 1;
    }
}