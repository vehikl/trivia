<?php

namespace App;

class Turn
{
    private $player;
    private $roll;
    private $game;

    private $view;

    public function __construct(Game $game, Roll $roll)
    {
        $this->player = $game->getCurrentPlayer();
        $this->roll = $roll;
        $this->game = $game;

        $this->view = new TurnView($this);
    }

    public function move()
    {
        $this->player->setRoll($this->roll);
        $this->displayPlayerRolls($this->roll->getValue());

        if ($this->player->isNotAllowedToMove()) {
            return $this->displayPlayerStaysInPenaltyBox();
        }

        if ($this->player->isInPenaltyBox()) {
            $this->displayPlayerGetsOutOfPenaltyBox();
        }

        $this->game->movePlayer($this->roll->getValue());
        $this->game->askQuestion();
    }

    protected function displayPlayerRolls($rolledNumber)
    {
        $this->view->echoln("{$this->player->getName()} is the current player");
        $this->view->echoln("They have rolled a {$rolledNumber}");
    }

    protected function displayPlayerStaysInPenaltyBox()
    {
        $this->view->echoln("{$this->player->getName()} is not getting out of the penalty box");
    }

    protected function displayPlayerGetsOutOfPenaltyBox()
    {
        $this->view->echoln("{$this->player->getName()} is getting out of the penalty box");
    }
}
