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
}
