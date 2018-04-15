<?php

use App\CommandLineView;
use App\Game;

function playGame($seed, $outputFile)
{
    srand($seed);
    $obFile = fopen($outputFile, 'w+');
    ob_start(function ($buffer) use ($obFile) {
        fwrite($obFile, $buffer);
    });

    $notAWinner;

    $view = new CommandLineView();
    $aGame = new Game($view);

    $aGame->add("Chet");
    $aGame->add("Pat");
    $aGame->add("Sue");

    do {
        $aGame->roll(rand(0, 5) + 1);

        if (rand(0, 9) == 7) {
            $notAWinner = $aGame->wrongAnswer();
        } else {
            $notAWinner = $aGame->wasCorrectlyAnswered();
        }
    } while ($notAWinner);

    ob_end_flush();
}
