<?php

namespace App;

class Turn
{
    private $board;
    private $questions;
    private $player;
    private $roll;
    private $startPlace;
    private $game;

    private $view;

    public function __construct(Game $game, Roll $roll)
    {
        $this->board = $game->getBoard();
        $this->questions = $game->getQuestions();
        $this->player = $game->getCurrentPlayer();
        $this->roll = $roll;
        $this->startPlace = $this->player->getPlace();
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
        $this->askQuestion();
    }

    private function movePlayer()
    {
        $this->player->moveTo($this->getEndPlace());
        $this->view->displayPlayerMoves();
    }

    private function askQuestion()
    {
        $this->view->displayCategory($this->getCategory());
        $this->view->displayQuestion($this->questions->askFrom($this->getCategory()));
    }

    private function getCategory()
    {
        return $this->getEndPlace()->getCategory();
    }

    private function getEndPlace()
    {
        return $this->board->findPlaceNumberOfPlacesFrom($this->startPlace, $this->roll->getValue());
    }

    private function isPlayerGettingOutOfPenaltyBox()
    {
        return $this->roll->isOdd();
    }
}
