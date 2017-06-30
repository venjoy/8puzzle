<?php 

namespace App;

class Game 
{
    use Concerns\HasSwap;
    protected $size;
    protected $initgame;
    protected $data;

    /*
      Dependancy Injection
    */
    
    public function __construct(InitGame $initGame)
    {
        $this->initGame = $initGame;
    }
    
    public function initFromPost($post)
    {
        $this->data = $this->initGame->fromPost($post);
        $this->size = count($this->data) * count($this->data) - 1;
        // swapIt
        $this->swapIt();
        // check win condition php
        if($this->checkWin())
        {
            echo"<html>";
            echo'<div class="game">Congrats,you win the game</div>';
        }
    }

    // public function initNextStep($post, $blankMovement, $size)
    // {
    //     $this->data = $this->initGame->fromPost($post);
    //     $this->size = $size;

    //     //swap
    //     $blankpos = $blankMovement[0];
    //     $numPos = $blankMovement[1];
    //     $this->swap($numPos[0], $numPos[1], $blankpos[0], $blankpos[1]);

    //     // check win condition php
    //     if($this->checkWin())
    //     {
    //         echo"<html>";
    //         echo'<div class="game">Congrats,you win the game</div>';
    //     }
    // }  

    public function setData($data)
    {
        $this->data = $data;
        $this->size = 8;
    } 

    public function getData()
    {
        return $this->data;
    }

    public function getValue($i, $j)
    {
        return $this->data[$i][$j];
    }

    public function getSide()
    {
        return sqrt($this->size + 1);
    }

    public function getSize()
    {
        return $this->size;
    }


    public function findposzero()
    {
         for($row=0;$row<$this->getside();$row++)
            {
                for($col=0;$col<$this->getside();$col++)
                {
                            if($this->data[$row][$col]==NULL)
                    {
                        $x=$row;
                        $y=$col;
                    }
                }
            }
            return array($x, $y);
    }

    public function checkWin()
    {
        $i=1;
        $win=true;
        $arr[$this->size+1];
        for($row=0;$row<$this->getSide();$row++)
            {
                for($col=0;$col<$this->getside();$col++)
                {
                    $arr[$i]=$this->data[$row][$col];
                    
                    $i++;
                }
            }
            
            for($j=1;$j<$this->size + 1;$j++)
            {
    
                
                    if($arr[$j]!=$j)
                    {
                        $win=false;
                    }
            }
            return $win;
    }

   public function swap($l,$m,$X,$Y)
    {
        $temp=$this->data[$l][$m];
        $this->data[$l][$m]=$this->data[$X][$Y];
        $this->data[$X][$Y]=$temp;
    }
    
    public function swapRandom()
    {       
        for($i=0;$i<100;$i++)
        {
           $rand=rand(1,4);
           if($rand=='1')
           {
                $findxy=$this->findposzero();
                $x=$findxy[0];
                $y=$findxy[1];
                if($this->data[$x-1][$y]!=NULL)
                {
                 $this->swap($x-1,$y,$x,$y);
                }
                
           }
           if($rand=='2')
           {
                $findxy=$this->findposzero();
                $x=$findxy[0];
                $y=$findxy[1];
                if($this->data[$x+1][$y]!=NULL)
                {
                    $this->swap($x+1,$y,$x,$y);
                }
                
           }
           if($rand=='3')
           {
               $findxy=$this->findposzero();
               $x=$findxy[0];
               $y=$findxy[1];
               if($this->data[$x][$y-1]!=NULL)
                {
                    $this->swap($x,$y-1,$x,$y);
                }
               
           }
           if($rand=='4')
           {
               $findxy=$this->findposzero();
               $x=$findxy[0];
               $y=$findxy[1];

               if($this->data[$x][$y+1]!=NULL)
                {
                    $this->swap($x,$y+1,$x,$y);
                }
               
           }
        }

    }
   public function swapIt()
    {
         // $_POST['btn-*-*']
        
        $findxy=$this->findposzero();
        $x=$findxy[0];
        $y=$findxy[1];
             foreach ($_POST as $key => $val)
            {
                 $exp = explode('-', $key);
                if(count($exp)>2)
                {
                  $var1=$exp[1];
                  $var2=$exp[2];  
                   if(($var1==$x && $var2==$y-1) || ($var1==$x && $var2==$y+1) || ($var1==$x-1 && $var2==$y) || ($var1==$x+1 && $var2==$y))  
                   {
                    $this->swap($var1,$var2,$x,$y);
                   }
                }
                
                   
            }
        
    }

    
    
     public function CurrPosNum($number)
     {
       $curPosNum=NULL;
       $this->eachPos(function($n,$pos) use (&$curPosNum,&$number){
           if($n==$number)
           {
               $curPosNum=$pos;
               return false;
           }
       });

       return ($curPosNum) ;
  
       
     }

      public function ActualPosNum($number)
      {
         $side=$this->getside();
         $temp=$number;
         $ActualPos=array(0,0);
         while(--$temp)
         {
             $ActualPos[1]=$ActualPos[1]+1;
             if($ActualPos[1] > $side-1)
             {
                 $ActualPos[0]=$ActualPos[0]+1;
                  $ActualPos[1]=$ActualPos[1] % $side;
             }
         }
         return ($ActualPos);
         
      }

      public function eachPos($callback)  //so that we need not to write 2 loops again and again...
      {
          for ($i = 0; $i < $this->getSide(); $i++)
          {
              for ($j = 0; $j < $this->getSide(); $j++)
              {
                  $pos = [$i, $j];
                  $ret = call_user_func_array($callback, [ $this->data[$i][$j], $pos ]);

                  if ($ret === false) break;
              }
          }
      }

      public function eachNum($callback)
      {
          for ($num = 1; $num <= $this->getSize(); $num++)
          {
              $pos = $this->CurrPosNum($num);
              $ret = call_user_func_array($callback, [ $num, $pos ]);

              if ($ret === false) break;
          }
      }

      public function curBlankPos()
      {
          $curPos = null;

          $this->eachPos(function($n, $pos) use (&$curPos) {
            if ($n == '') {
                $curPos = $pos;
                return false;
            }
          });

          return $curPos;
      }
}