<?php

namespace App;

class Game
{
    private $players = [];
    private $currentPlayerId = 0;
    private $questions;

    private $turn;

    private $view;

    const CATEGORIES = ["Pop", "Science", "Sports", "Rock"];
    const QUESTIONS_PER_CATEGORY = 50;
    const NUMBER_OF_PLACES = 12;

    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {
        $this->board = new GameBoard(self::CATEGORIES, self::NUMBER_OF_PLACES);
        $this->questions = new GameQuestions(self::CATEGORIES, self::QUESTIONS_PER_CATEGORY);
        $this->view = new View($this);
    }

    public function add($playerName)
    {
        $this->players[] = new Player($playerName);
        $this->displayPlayerIsAdded($playerName);
    }

    private function howManyPlayers()
    {
        return count($this->players);
    }

    public function roll($rolledNumber)
    {
        $this->turn = new Turn($this, new Roll($rolledNumber));
        $this->turn->action();
    }

    public function askQuestion()
    {
        $this->displayCategory();
        $this->displayQuestion($this->questions->askFrom($this->currentCategory()));
    }

    private function currentCategory()
    {
        $popPlaces = [0, 4, 8];
        $sciencePlaces = [1, 5, 9];
        $sportsPlaces = [2, 6, 10];

        if (in_array($this->getCurrentPlayer()->getPlace(), $popPlaces)) {
            return "Pop";
        }

        if (in_array($this->getCurrentPlayer()->getPlace(), $sciencePlaces)) {
            return "Science";
        }

        if (in_array($this->getCurrentPlayer()->getPlace(), $sportsPlaces)) {
            return "Sports";
        }

        return "Rock";
    }

    public function wasCorrectlyAnswered()
    {
        return $this->correctAnswer();
    }

    public function correctAnswer()
    {
        if ($this->getCurrentPlayer()->isAllowedToAnswer()) {
            $this->displayCorrectAnswer($this->useCorrent());
            $this->givePlayerGoldCoin();
        }

        return $this->passTheDice();
    }

    public function wrongAnswer()
    {
        $this->displayWrongAnswer();
        $this->sendPlayerToPenaltyBox();

        return $this->passTheDice();
    }

    private function givePlayerGoldCoin()
    {
        $this->getCurrentPlayer()->addCoin();
        $this->displayPlayerReceivesGoldCoin();
    }

    private function sendPlayerToPenaltyBox()
    {
        $this->getCurrentPlayer()->receivePenalty();
        $this->displayPlayerIsSentToPenaltyBox();
    }

    private function passTheDice()
    {
        $nextId = $this->currentPlayerId + 1;
        $this->currentPlayerId = $nextId < $this->howManyPlayers() ? $nextId : 0;
        return $this->gameIsNotOver();
    }

    private function gameIsNotOver()
    {
        return !array_reduce($this->players, function ($result, $player) {
            return $result || $player->getCoins() == self::GOLD_COINS_TO_WIN;
        }, false);
    }

    public function getCurrentPlayer()
    {
        return $this->players[$this->currentPlayerId];
    }

    public function getBoard()
    {
        return $this->board;
    }

    private function useCorrent()
    {
        return $this->getCurrentPlayer()->isNotInPenaltyBox();
    }

    protected function displayPlayerIsAdded($playerName)
    {
        $this->view->echoln("{$playerName} was added");
        $this->view->echoln("They are player number {$this->howManyPlayers()}");
    }

    protected function displayCategory()
    {
        $this->view->echoln("The category is {$this->currentCategory()}");
    }

    protected function displayQuestion($question)
    {
        $this->view->echoln($question);
    }

    protected function displayCorrectAnswer($withTypo)
    {
        $this->view->echoln("Answer was " . ($withTypo ? 'corrent' : 'correct') . "!!!!");
    }

    protected function displayWrongAnswer()
    {
        $this->view->echoln("Question was incorrectly answered");
    }

    protected function displayPlayerIsSentToPenaltyBox()
    {
        $this->view->echoln("{$this->getCurrentPlayer()->getName()} was sent to the penalty box");
    }

    protected function displayPlayerReceivesGoldCoin()
    {
        $this->view->echoln("{$this->getCurrentPlayer()->getName()} now has {$this->getCurrentPlayer()->getCoins()} Gold Coins.");
    }
}
