<?php

namespace App\AI;

use App\Contracts\PathFinder as PathFinderContract;
use App\Game;

class PathFinder implements PathFinderContract
{
    protected $game;

    public function __construct(Game $game)
    {
        $this->game = $game;
    }

    public function findPaths($curBlankPos, $finBlankPos, $wrtNum)
    {         
        $path = [$curBlankPos];
        $paths = [$path];
        $paths = $this->explorePaths($paths, $finBlankPos, $wrtNum);
        return $paths;
    }

    public function explorePaths($paths, $finBlankPos, $wrtNum)
    {        
        $newPaths = [];
        $oldPaths = [];
        foreach ($paths as $path)
        {
            if ($this->isIncomplete($path, $finBlankPos))
            {
                $newPathsArray = $this->expandPath($path, $finBlankPos, $wrtNum);
                foreach ($newPathsArray as $newPath)
                {
                    $newPaths[] = $newPath;
                    $allPaths[] = $newPath;
                }               
            }
            else
            {
                $allPaths[] = $path;
            }
            
        }
        
        return (count($newPaths) == 0) ? $paths : $this->explorePaths($allPaths, $finBlankPos, $wrtNum);        
    }

    public function expandPath($path, $finBlankPos, $wrtNum)
    {        
        $newPaths = [];
        $leafPos = $this->findLeafPos($path);
        for ($dir = 1; $dir<=4; $dir++)
        {
            $newpath = [];
            //$i=1 for up, 2 for right,3 for down and 4 for left...
            $newPos = $this->findNewPos($leafPos, $dir);
            if($this->isFeasible($newPos, $wrtNum, $path))
            {
                //adding new pos to path and saving to newPaths...
                $newPath = $path;
                $newPath[] = $newPos;
                $newPaths[] = $newPath;
            }
        }

        return $newPaths;
    }

    public function findLeafPos($path)
    {
        $pathLength = count($path)-1;
        $leafPos = $path[$pathLength];

        return $leafPos;
    }

    public function findNewPos($leafPos, $dir)
    {
        switch ($dir){
                Case 1:
                    $rowChange = -1;
                    $colChange = 0;
                    break;
                Case 2:
                    $rowChange = 0;
                    $colChange = 1;
                    break;
                Case 3:
                    $rowChange = 1;
                    $colChange = 0;
                    break;
                Case 4:
                    $rowChange = 0;
                    $colChange = -1;
                    break;
            }
            $newPos = [$leafPos[0]+$rowChange, $leafPos[1]+$colChange];

            return $newPos;
    }

    public function isFeasible($pos, $wrtNum, $path)
    {
        return ($this->inBounds($pos) && $this->swapAllowed($pos, $wrtNum) && $this->notInPath($path, $pos)) ? 1 : 0;
    }

    public function inBounds($pos)
    {
        $side = $this->game->getSide();

        return ($pos[0] <= $side-1 && $pos[0] >= 0 && $pos[1] <= $side-1 && $pos[1] >= 0) ? 1 : 0;
    }

    public function swapAllowed($pos, $wrtNum)
    {
        $data = $this->game->getData();
        $numAtPos = $data[$pos[0]][$pos[1]];
        
        return ($numAtPos > $wrtNum) ? 1 : 0;
    }

    public function notInPath($path, $pos)
    {
        $notInPath = 1;
        foreach ($path as $traversedPos)
        {
            if($traversedPos == $pos)
            {
                $notInPath = 0;
            }
        }

        return $notInPath;
    }

    public function isIncomplete($path, $finBlankPos)
    {
        $lastIndex = count($path) - 1;

        return ($path[$lastIndex] == $finBlankPos) ? 0 : 1;
    }
}