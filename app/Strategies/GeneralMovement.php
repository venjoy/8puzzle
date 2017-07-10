<?php

namespace App\Strategies;

use App\Contracts\Movement;
use App\Game;
use App\AI\PathFinder;

class GeneralMovement implements Movement
{
    protected $game;
    protected $pathFinder;
    protected $deadlockMovement;

    public function __construct(Game $game, PathFinder $pathFinder, DeadlockMovement $deadlockMovement)
    {
        $this->game = $game;
        $this->pathFinder = $pathFinder;
        $this->deadlockMovement = $deadlockMovement;    
    }

    public function findNextMove($wrongNum)
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
            $movements = movementsFromPath($bestPath);
        }

        //deadlock for 5 when blank is at [2,0], 4 above it and 5 at pos right of it
        //this deadlock can come for 10,11 in case of 4*4 matrix
        if ($movements == NULL)
        {
            $movements = $this->deadlockMovement->getRotationMoves($wrongNum);
        }

        return ($movements);
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
        $ActualPos = $this->game->ActualPosNum($number);

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
        else
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
        
        return $BlankFinalPos;
    }
}