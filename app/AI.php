<?php 

namespace App;

use App\Contracts\PathFinder;

class AI 
{
    protected $game;
    protected $pathFinder;

    public function __construct(Game $game, PathFinder $pathFinder)
    {
        $this->game = $game;
        $this->pathFinder = $pathFinder;
    }

    public function nextStep()
    {
        $wrongNum = $this->findNumOnWrongPos();
        $blankPos = $this->game->curBlankPos();
                var_dump($blankPos);
        die();
        $finalBlankPos = $this->posBlankWrtNum($wrongNum);
        $paths = $this->pathFinder->findPaths( $blankPos, $finalBlankPos, $wrongNum);
        // $bestPath = $this->findBestPath($paths);
        // var_dump($bestPath);
        // die();
        // $blankMovement = [];
        // $blankMovement[] = $bestPath[0];
        // $blankMovement[] = $bestPath[1];
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