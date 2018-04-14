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
        $this->movePlayer($this->roll->getValue());
        $this->game->askQuestion();
    }

    private function movePlayer($roll)
    {
        $currentSpace = $this->player->getPlace();
        $this->player->moveTo($this->board->findPlaceNumberOfPlacesFromCurrentPlace($currentSpace, $roll));
        $this->view->displayPlayerMoves();
    }

    private function isPlayerGettingOutOfPenaltyBox()
    {
        return $this->roll->isOdd();
    }
}
