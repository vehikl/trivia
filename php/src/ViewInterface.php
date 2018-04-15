<?php

namespace App;

interface ViewInterface
{
    public function setPlayer($player);
    public function displayPlayerIsAdded($playerName, $totalPlayers);
    public function displayPlayerRolls($rolledNumber);
    public function displayPlayerStaysInPenaltyBox();
    public function displayPlayerGetsOutOfPenaltyBox();
    public function displayPlayerMoves();
    public function displayCategory($category);
    public function displayQuestion($question);
    public function displayCorrectAnswer($withTypo);
    public function displayPlayerReceivesGoldCoin();
    public function displayWrongAnswer();
    public function displayPlayerIsSentToPenaltyBox();
}
