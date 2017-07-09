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

        //placing corner element at [cornerRow+1][cornerCol-1]
        $tempPos = getTempPosForCorner($wrongNum, $this->game->getSide());
        if ($this->game->getValue($tempPos[0], $tempPos[1]) != $wrongNum)
        {
            $movements = $this->moveCornerToReq($wrongNum);
            return $movements;
        }

        //placing blank at required position
        if ($this->game->getValue($tempPos[0],0) != NULL)
        {
            $movements = $this->moveBlank($wrongNum);
            return $movements;
        }

        //performing cyclic movement
        if($this->game->getValue($tempPos[0], $tempPos[1]) == $wrongNum && $this->game->getValue($tempPos[0],0) == NULL)
        {
            $movements = $this->cyclicMovement($wrongNum);
            return $movements;
        }
    }

    public function cyclicMovement($wrongNum)
    {
        $movements = cyclicMovesArray($this->game->getSide(), $wrongNum);

        return $movements;
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
            $movements = [$movement];
        }
        else
        {
            $paths = $this->pathFinder->findPaths( $blankPos, $finalBlankPos, $wrongNum);           
            $bestPath = $this->findBestPath($paths);
            $movements = movementsArray($bestPath);
        }

        return ($movements);
    }

    public function moveBlank($wrongNum)
    {
        $blankPos = $this->game->curBlankPos();
        $row = $wrongNum / $this->game->getSide();
        $finalBlankPos = [$row,0];
        $paths = $this->pathFinder->findPaths( $blankPos, $finalBlankPos, $wrongNum);
        $bestPath = $this->findBestPath($paths);
        $movements = movementsArray($bestPath);

        return $movements;
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