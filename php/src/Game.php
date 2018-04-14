<?php

namespace App;

class Game
{
    private $players = [];
    private $currentPlayerId = 0;
    private $questions = [];

    private $turn;

    private $view;

    const QUESTIONS_PER_CATEGORY = 50;
    const TOTAL_PLACES = 12;
    const LAST_PLACE = 11;
    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {
        $this->initializeQuestions();
        $this->view = new View($this);
    }

    private function initializeQuestions()
    {
        foreach (["Pop", "Science", "Sports", "Rock"] as $category) {
            $this->initializeCategoryQuestions($category);
        }
    }

    private function initializeCategoryQuestions($category)
    {
        $this->questions[strtolower($category)] = array_map(function ($number) use ($category) {
            return $this->createQuestion($category, $number);
        }, range(0, self::QUESTIONS_PER_CATEGORY - 1));
    }

    private function createQuestion($category, $number)
    {
        return "{$category} Question {$number}";
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

    public function movePlayer($roll)
    {
        $newPlace = $this->getCurrentPlayer()->getSpace() + $roll;
        $newPlace = self::LAST_PLACE >= $newPlace ? $newPlace : $newPlace - self::TOTAL_PLACES;
        $this->getCurrentPlayer()->setSpace($newPlace);
        $this->displayPlayerMoves();
    }

    public function askQuestion()
    {
        $this->displayCategory();
        $category = strtolower($this->currentCategory());
        $this->displayQuestion(array_shift($this->questions[$category]));
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

    protected function displayPlayerMoves()
    {
        $this->view->echoln("{$this->getCurrentPlayer()->getName()}'s new location is {$this->getCurrentPlayer()->getSpace()}");
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
