<?php

namespace App;

class TurnView
{
    private $player;

    public function __construct(Player $player)
    {
        $this->player = $player;
    }

    public function echoln($string)
    {
        echo $string . "\n";
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
        $this->echoln("{$this->player->getName()}'s new location is {$this->player->getPlace()->getLocation()}");
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
