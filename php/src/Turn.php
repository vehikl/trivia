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

        $this->view = new TurnView($this->player);
    }

    public function move()
    {
        $this->player->setRoll($this->roll);
        $this->view->displayPlayerRolls($this->roll->getValue());

        if ($this->player->isNotAllowedToMove()) {
            return $this->view->displayPlayerStaysInPenaltyBox();
        }

        if ($this->player->isInPenaltyBox()) {
            $this->view->displayPlayerGetsOutOfPenaltyBox();
        }

        $this->game->movePlayer($this->roll->getValue());
        $this->game->askQuestion();
    }
}
