<?php require_once __DIR__ . '/vendor/autoload.php' ?>

<?php session_start(); ?>

<html>
    <head>
    <link rel="stylesheet" type="text/css" href="game.css">
    </head>
    <body>
        <?php 
            $game = app(App\Game::class);
    
            if (!isset($_POST['0-0']) && !isset($_POST['0-1']))
            {
                //fromSize() also unsets session variables ai and cyclic
                app('App\Initializer')->fromSize();
            } 
            else 
            {
                if(($_POST['next'] == 'next') && ! ($_SESSION['ai']))
                {
                    $_SESSION['movements'] = [];
                    $_SESSION['index'] = 0;
                    $_SESSION['ai'] = true;

                    //storing movements
                    app('App\Initializer')->fromPost($_POST);
                    // while (! ($game->checkWin()))
                    for ($i=1;$i<=20;$i++)
                    {
                        $movements = app('App\AI')->nextMove();

                        foreach ($movements as $movement)
                        {
                            $_SESSION['movements'][] = $movement;
                            app('App\Initializer')->initNextMove($movement);
                        }
                    }

                    //running first move
                    app('App\Initializer')->fromPost($_POST);
                    $movement = $_SESSION['movements'][$_SESSION['index']];
                    $_SESSION['index']++;
                    app('App\Initializer')->initNextMove($movement);
                }
                else if (($_POST['next'] == 'next') && $_SESSION['ai'])
                {
                    app('App\Initializer')->fromPost($_POST);
                    $movement = $_SESSION['movements'][$_SESSION['index']];
                    $_SESSION['index']++;
                    app('App\Initializer')->initNextMove($movement);                
                }
                else if($_POST['back'] == 'back')
                {
                    app('App\Initializer')->fromPost($_POST);
                    $_SESSION['index']--;
                    $movement = $_SESSION['movements'][$_SESSION['index']];
                    app('App\Initializer')->initNextMove($movement);
                }
                else
                {
                        app('App\Initializer')->fromPost($_POST);
                        $_SESSION['ai'] = false;
                }
            }
        ?>

        <?php
            if (isset($_SESSION['ai']) && $_SESSION['ai']) {
                include __DIR__ . '/views/game-user.php';                
            } 
            else {
                include __DIR__ . '/views/game-user.php';
            }
        ?>
                
    </body>
</html>

