<?php

namespace App\Strategies;

use App\Game;

class DeadlockMovement
{
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;  
    }

    public function getRotationMoves($wrongNum)
    {
        $path = [];
        $blankPos = $this->game->curBlankPos();
        $currPos = $blankPos;

        //finding path for rotation
        while (app('App\AI\PathFinder')->inBounds($currPos))
        {
            $path[] = $currPos;
            $currPos = goLeft($currPos);
        }
        $currPos = goRight($currPos);//come back
        $currPos = goUp($currPos);
        while (app('App\AI\PathFinder')->inBounds($currPos))
        {
            $path[] = $currPos;
            $currPos = goRight($currPos);
        }
        $currPos = goLeft($currPos);//come back
        $currPos = goDown($currPos);
        while ($currPos != $blankPos)
        {
            $path[] = $currPos;
            $currPos = goleft($currPos);
        }

        $movements = movementsFromPath($path);
        return $movements;
    }

    public function getCyclicMoves($side, $wrongNum)
    {
        $path = [];
        $tempPos = getTempPosForCorner($wrongNum, $side);
        $currPos = [$tempPos[0], 0];
        //computing path array for the cycle
        $path[] = $currPos;
        $currPos = goUp($currPos);
        $path[] = $currPos;
        for ($i = 1; $i <= $side-2; $i++)
        {
            $currPos = goRight($currPos);
            $path[] = $currPos;
        }
        $currPos = goDown($currPos);
        $path[] = $currPos;
        $currPos = goRight($currPos);
        $path[] = $currPos;
        $currPos = goUp($currPos);
        $path[] = $currPos;
        for ($i = 1; $i <= $side-1; $i++)
        {
            $currPos = goLeft($currPos);
            $path[] = $currPos;
        }
        $currPos = goDown($currPos);
        $path[] = $currPos;

        $movements = movementsFromPath($path);
        return $movements;
    }
}