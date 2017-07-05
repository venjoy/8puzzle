<?php require_once __DIR__ . '/vendor/autoload.php' ?>

<?php session_start(); ?>

<html>
    <head>
    <link rel="stylesheet" type="text/css" href="game.css">
    </head>
    <body>
        <?php 
            $game = app(App\Game::class);
            $_SESSION['ai'] = false;
    
            if (!isset($_POST['0-0']) && !isset($_POST['0-1']))
            {
                app('App\Initializer')->fromSize();
            } 
            else 
            {
                if($_POST['next'] == 'next')
                {
                    $_SESSION['ai'] = true;
                    // $pathFinder = new App\AI\PathFinder($game);
                    // $ai = new App\AI($game, $pathFinder);
                    // $s = $ai->nextStep();
                    app('App\Initializer')->fromPost($_POST);
                    $blankMovement = app('App\AI')->nextMove();
                    app('App\Initializer')->initNextMove($blankMovement);
                }
                else
                {
                        app('App\Initializer')->fromPost($_POST);
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

