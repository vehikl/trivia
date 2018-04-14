<?php

namespace App;

class Turn
{
    private $player;
    private $board;
    private $roll;
    private $game;

    private $view;

    const TOTAL_PLACES = 12;
    const LAST_PLACE = 11;

    public function __construct(Game $game, Roll $roll)
    {
        $this->player = $game->getCurrentPlayer();
        $this->board = $game->getBoard();
        $this->roll = $roll;
        $this->game = $game;

        $this->view = new TurnView($this->player);
    }

    public function action()
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

    private function isPlayerAllowedToMove()
    {
        $this->isNotInPenaltyBox() || $this->roll->isOdd();
    }

    private function movePlayer($roll)
    {
        $currentSpace = $this->player->getSpace();
        $this->player->setSpace($this->board->findPlaceNumberOfPlacesFromCurrentPlace($currentSpace, $roll));
        $this->view->displayPlayerMoves();
    }
}
