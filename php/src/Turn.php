<?php

namespace App;

class Turn
{
    private $player;
    private $roll;
    private $game;

    private $view;

    const TOTAL_PLACES = 12;
    const LAST_PLACE = 11;

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

        $this->movePlayer($this->roll->getValue());
        $this->game->askQuestion();
    }

    private function movePlayer($roll)
    {
        $newPlace = $this->player->getSpace() + $roll;
        $newPlace = self::LAST_PLACE >= $newPlace ? $newPlace : $newPlace - self::TOTAL_PLACES;
        $this->player->setSpace($newPlace);
        $this->view->displayPlayerMoves();
    }
}
