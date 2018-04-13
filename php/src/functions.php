<?php

function echoln($string)
{
    echo $string."\n";
}

function playGame($seed, $outputFile)
{
    srand($seed);
    $obFile = fopen($outputFile, 'w+');
    ob_start(function ($buffer) use ($obFile) {
        fwrite($obFile, $buffer);
    });

    $aGame = new Game();

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
