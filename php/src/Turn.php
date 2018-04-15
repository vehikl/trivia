<?php

namespace App;

class Turn
{
    private $startPlace;
    private $roll;
    private $player;
    private $board;
    private $questions;

    private $view;

    public function __construct($player, $roll, $board, $questions, $view)
    {
        $this->startPlace = $player->getPlace();
        $this->roll = $roll;
        $this->player = $player;
        $this->board = $board;
        $this->questions = $questions;

        $this->view = $view;
        $this->view->setPlayer($player);
    }

    public function action()
    {
        $this->view->displayPlayerRolls($this->roll->getValue());
        $this->player->isInPenaltyBox() ? $this->penaltyAction() : $this->regularAction();
    }

    private function regularAction()
    {
        $this->movePlayer();
        $this->askQuestion();
    }

    private function penaltyAction()
    {
        $this->isPenaltyLifted() ? $this->penaltyLiftedAction() : $this->penaltyStaysAction();
    }

    private function penaltyLiftedAction()
    {
        $this->view->displayPlayerGetsOutOfPenaltyBox();
        $this->regularAction();
    }

    private function penaltyStaysAction()
    {
        $this->view->displayPlayerStaysInPenaltyBox();
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

    public function correctAnswer()
    {
        $this->isPlayerAllowedToAnswer() && $this->correctAnswerWithPermission();
    }

    private function correctAnswerWithPermission()
    {
        $this->view->displayCorrectAnswer($this->useCorrent());
        $this->givePlayerGoldCoin();
    }

    private function givePlayerGoldCoin()
    {
        $this->player->addCoin();
        $this->view->displayPlayerReceivesGoldCoin();
    }

    public function wrongAnswer()
    {
        $this->view->displayWrongAnswer();
        $this->sendPlayerToPenaltyBox();
    }

    private function sendPlayerToPenaltyBox()
    {
        $this->player->receivePenalty();
        $this->view->displayPlayerIsSentToPenaltyBox();
    }

    private function getCategory()
    {
        return $this->getEndPlace()->getCategory();
    }

    private function getEndPlace()
    {
        return $this->board->findPlace($this->startPlace->getPosition() + $this->roll->getValue());
    }

    private function isPlayerAllowedToAnswer()
    {
        return !$this->player->isInPenaltyBox() || $this->isPenaltyLifted();
    }

    private function isPenaltyLifted()
    {
        return $this->roll->isOdd();
    }

    private function useCorrent()
    {
        return !$this->player->isInPenaltyBox();
    }
}
