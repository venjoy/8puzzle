<?php 

namespace App;

class InitGame
{
    public function fromSize($size)
    {
        $data = [];
        // $k = 1;
        // $side = sqrt($size + 1);
            
        // for($row = 0; $row < $side; $row++)
        // {
        //     for($col = 0; $col < $side; $col++)
        //     {
        //         $data[$row][$col] = $k;
        //         $k++;
        //     }
        // }

        // $data[$side-1][$side-1] = NULL;
        $data[0][0]=2;
        $data[0][1]=3;
        $data[0][2]=5;
        $data[1][0]=7;
        $data[1][1]=6;
        $data[1][2]=4;
        $data[2][0]=1;
        $data[2][2]=8;

        return $data;
    }

    public function fromPost($post)
    {
        $data = [];
        foreach ($post as $key => $val)
        {
            $exp = explode('-', $key);
            // check btn-1-1 
            if (count($exp) !== 2) continue;
            $row = $exp[0];
            $col = $exp[1];
            $data[$row][$col] = $val;
        }
        
        return $data;
    }
}