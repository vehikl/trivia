<?php

namespace App;

class GameView extends View implements ViewInterface
{
    private $player;

    public function setPlayer($player)
    {
        $this->player = $player;
    }

    public function displayPlayerIsAdded($playerName, $totalPlayers)
    {
        $this->echoln("{$playerName} was added");
        $this->echoln("They are player number {$totalPlayers}");
    }

    public function displayPlayerRolls($rolledNumber)
    {
        $this->echoln("{$this->player->getName()} is the current player");
        $this->echoln("They have rolled a {$rolledNumber}");
    }

    public function displayPlayerStaysInPenaltyBox()
    {
        $this->echoln("{$this->player->getName()} is not getting out of the penalty box");
    }

    public function displayPlayerGetsOutOfPenaltyBox()
    {
        $this->echoln("{$this->player->getName()} is getting out of the penalty box");
    }

    public function displayPlayerMoves()
    {
        $this->echoln("{$this->player->getName()}'s new location is {$this->player->getPlace()->getPosition()}");
    }

    public function displayCategory($category)
    {
        $this->echoln("The category is {$category}");
    }

    public function displayQuestion($question)
    {
        $this->echoln($question);
    }

    public function displayCorrectAnswer($withTypo)
    {
        $this->echoln("Answer was " . ($withTypo ? 'corrent' : 'correct') . "!!!!");
    }

    public function displayPlayerReceivesGoldCoin()
    {
        $this->echoln("{$this->player->getName()} now has {$this->player->getCoins()} Gold Coins.");
    }

    public function displayWrongAnswer()
    {
        $this->echoln("Question was incorrectly answered");
    }

    public function displayPlayerIsSentToPenaltyBox()
    {
        $this->echoln("{$this->player->getName()} was sent to the penalty box");
    }
}
