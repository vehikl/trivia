<?php

namespace App;

class Game
{
    private $players = [];
    private $currentPlayerId = 0;
    private $questions;

    private $turn;

    private $view;

    const QUESTION_CATEGORIES = ["Pop", "Science", "Sports", "Rock"];
    const QUESTIONS_PER_CATEGORY = 50;

    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {
        $this->questions = new GameQuestions(self::QUESTION_CATEGORIES, self::QUESTIONS_PER_CATEGORY);
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
        $this->turn->move();
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

        if (in_array($this->getCurrentPlayer()->getSpace(), $popPlaces)) {
            return "Pop";
        }

        if (in_array($this->getCurrentPlayer()->getSpace(), $sciencePlaces)) {
            return "Science";
        }

        if (in_array($this->getCurrentPlayer()->getSpace(), $sportsPlaces)) {
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
