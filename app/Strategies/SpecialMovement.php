<?php

namespace App\Strategies;

use App\Contracts\Movement;
use App\Game;
use App\AI\PathFinder;

class SpecialMovement implements Movement
{
    protected $game;
    protected $pathFinder;

    public function __construct(Game $game, PathFinder $pathFinder)
    {
        $this->game = $game;
        $this->pathFinder = $pathFinder;    
    }

    public function findNextMove($wrongNum)
    {
        //case when 3 is at [1,2] and blank at [0,2]


        //performing cyclic movement
        if ($_SESSION['cyclic'])
        {
            $movement = $this->cyclicMovement($wrongNum);
            return $movement;
        }

        //placing corner element at [cornerRow+1][cornerCol-1]
        $tempPos = getTempPosForCorner($wrongNum, $this->game->getSide());
        if ($this->game->getValue($tempPos[0], $tempPos[1]) != $wrongNum)
        {
            $movement = $this->moveCornerToReq($wrongNum);
            return $movement;
        }

        //placing blank at required position
        if ($this->game->getValue($tempPos[0],0) != NULL)
        {
            $movement = $this->moveBlank($wrongNum);
            return $movement;
        }

        //setting cyclic movement and performing
        if($this->game->getValue($tempPos[0], $tempPos[1]) == $wrongNum && $this->game->getValue($tempPos[0],0) == NULL)
        {
            $_SESSION['cyclic'] = 1;
            $movement = $this->cyclicMovement($wrongNum);
            return $movement;
        }
    }

    public function cyclicMovement($wrongNum)
    {
        $movements = cyclicMovesArray($this->game->getSide(), $wrongNum);
    }

    public function moveCornerToReq($wrongNum)
    {        
        $blankPos = $this->game->curBlankPos();
        $finalBlankPos = $this->posBlankWrtNum($wrongNum);
        if ($blankPos == $finalBlankPos)
        {
            $movement = [];
            $movement[] = $blankPos;
            $movement[] = $this->game->CurrPosNum($wrongNum);
        }
        else
        {
            $paths = $this->pathFinder->findPaths( $blankPos, $finalBlankPos, $wrongNum);           
            $bestPath = $this->findBestPath($paths);
            $movement = [];
            $movement[] = $bestPath[0];
            $movement[] = $bestPath[1];
        }

        return ($movement);
    }

    public function moveBlank($wrongNum)
    {
        $blankPos = $this->game->curBlankPos();
        $row = $wrongNum / $this->game->getSide();
        $finalBlankPos = [$row,0];
        $paths = $this->pathFinder->findPaths( $blankPos, $finalBlankPos, $wrongNum);
        $bestPath = $this->findBestPath($paths);
        $movement = [];
        $movement[] = $bestPath[0];
        $movement[] = $bestPath[1];

        return $movement;
    }

    public function findBestPath($paths)
    {
        $bestPath = $paths[0];
        foreach ($paths as $path)
        {
            $bestPath = count($path) < count($bestPath) ? $path : $bestPath ;
        }

        return $bestPath;
    }


    public function posBlankWrtNum($number)
    {
        $CurrPos = $this->game->CurrPosNum($number);
        $col = ($number - 2) % $this->game->getSide();
        $row = $number / $this->game->getSide();
        $ActualPos =[$row, $col];

        $DiffInPos = array(0,0);
        $BlankFinalPos = array(0,0);
        $DiffInPos[0] = $CurrPos[0]-$ActualPos[0];
        $DiffInPos[1] = $CurrPos[1]-$ActualPos[1];

        if($DiffInPos[1] != 0)
        {
            if($DiffInPos[1] > 0)
            {
                $BlankFinalPos[0]=$CurrPos[0]+0;
                $BlankFinalPos[1]=$CurrPos[1]-1;
            }
            else
            {
                $BlankFinalPos[0]=$CurrPos[0]+0;
                $BlankFinalPos[1]=$CurrPos[1]+1;
            }
        }
        else if ($DiffInPos[0] != 0)
        {
            if($DiffInPos[0]>0)
            {
                $BlankFinalPos[0]=$CurrPos[0]-1;
                $BlankFinalPos[1]=$CurrPos[1]+0;
            }
            else
            {
                $BlankFinalPos[0]=$CurrPos[0]+1;
                $BlankFinalPos[1]=$CurrPos[1]+0;
            }
        }
        else
        {
            $BlankFinalPos = [1][0];
        }
        
        return ($BlankFinalPos);
    }
}