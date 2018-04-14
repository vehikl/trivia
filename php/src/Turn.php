<?php

namespace App;

class Turn
{
    private $player;
    private $board;
    private $roll;
    private $game;

    private $view;

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
        $this->player->isInPenaltyBox() ? $this->penaltyAction() : $this->regularAction();
    }

    private function penaltyAction()
    {
        if (!$this->isPlayerGettingOutOfPenaltyBox()) {
            return $this->view->displayPlayerStaysInPenaltyBox();
        }

        $this->view->displayPlayerGetsOutOfPenaltyBox();
        $this->regularAction();
    }

    private function regularAction()
    {
        $this->movePlayer();
        $this->game->askQuestion();
    }

    private function movePlayer()
    {
        $current = $this->player->getPlace();
        $place = $this->board->findPlaceNumberOfPlacesFromCurrentPlace($current, $this->roll->getValue());
        $this->player->moveTo($place);
        $this->view->displayPlayerMoves();
    }

    private function isPlayerGettingOutOfPenaltyBox()
    {
        return $this->roll->isOdd();
    }
}
