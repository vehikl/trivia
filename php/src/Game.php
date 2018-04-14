<?php

class Game
{
    private $players = [];
    private $currentPlayerId = 0;

    private $questions = [];

    const QUESTIONS_PER_CATEGORY = 50;

    const TOTAL_PLACES = 12;
    const LAST_PLACE = 11;

    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {
        $this->initializeQuestions();
    }

    private function initializeQuestions()
    {
        $numbers = range(0, self::QUESTIONS_PER_CATEGORY - 1);

        foreach (["Pop", "Science", "Sports", "Rock"] as $category) {
            $this->initializeCategoryQuestions($category, $numbers);
        }
    }

    private function initializeCategoryQuestions($category, $numbers)
    {
        $this->questions[strtolower($category)] = array_map(function ($number) use ($category) {
            return $this->createQuestion($category, $number);
        }, $numbers);
    }

    private function createQuestion($category, $number)
    {
        return "{$category} Question {$number}";
    }

    public function add($playerName)
    {
        $this->players[] = new Player($playerName);
        echoln("{$playerName} was added");
        echoln("They are player number {$this->howManyPlayers()}");
    }

    private function howManyPlayers()
    {
        return count($this->players);
    }

    public function roll($value)
    {
        $roll = new Roll($value);
        echoln("{$this->getCurrentPlayer()->getName()} is the current player");
        echoln("They have rolled a {$roll->getValue()}");

        if ($this->isNotAllowedOutOfPenaltyBox($this->getCurrentPlayer(), $roll)) {
            echoln("{$this->getCurrentPlayer()->getName()} is not getting out of the penalty box");
            $this->getCurrentPlayer()->setIsGettingOutOfPenaltyBox(false);
            return;
        }

        if ($this->getCurrentPlayer()->getIsInPenaltyBox()) {
            $this->getCurrentPlayer()->setIsGettingOutOfPenaltyBox(true);
            echoln("{$this->getCurrentPlayer()->getName()} is getting out of the penalty box");
        }

        $this->movePlayer($roll->getValue());
        $this->askQuestion();
    }

    protected function isNotAllowedOutOfPenaltyBox($player, $roll)
    {
        return $player->getIsInPenaltyBox() && $roll->isEven();
    }

    private function movePlayer($roll)
    {
        $this->getCurrentPlayer()->setSpace($this->getCurrentPlayer()->getSpace() + $roll);
        if ($this->getCurrentPlayer()->getSpace() > self::LAST_PLACE) {
            $this->getCurrentPlayer()->setSpace($this->getCurrentPlayer()->getSpace() - self::TOTAL_PLACES);
        }

        echoln("{$this->getCurrentPlayer()->getName()}'s new location is {$this->getCurrentPlayer()->getSpace()}");
    }

    private function askQuestion()
    {
        echoln("The category is {$this->currentCategory()}");
        $category = strtolower($this->currentCategory());
        echoln(array_shift($this->questions[$category]));
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
        if ($this->getCurrentPlayer()->getIsInPenaltyBox() && $this->getCurrentPlayer()->getIsGettingOutOfPenaltyBox()) {
            echoln("Answer was correct!!!!");
            return $this->givePlayerGoldCoin();
        } elseif ($this->getCurrentPlayer()->getIsInPenaltyBox()) {
            $this->passTheDice();
            return $this->gameIsNotOver();
        } else {
            echoln("Answer was corrent!!!!");
            return $this->givePlayerGoldCoin();
        }
    }

    private function givePlayerGoldCoin()
    {
        $this->getCurrentPlayer()->addCoin();
        echoln("{$this->getCurrentPlayer()->getName()} now has {$this->getCurrentPlayer()->getCoins()} Gold Coins.");
        $this->passTheDice();

        return $this->gameIsNotOver();
    }

    public function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln("{$this->getCurrentPlayer()->getName()} was sent to the penalty box");
        $this->getCurrentPlayer()->setIsInPenaltyBox(true);
        $this->passTheDice();

        return $this->gameIsNotOver();
    }

    private function passTheDice()
    {
        $this->currentPlayerId++;
        if ($this->currentPlayerId == count($this->players)) {
            $this->currentPlayerId = 0;
        }
    }

    private function gameIsNotOver()
    {
        return !$this->gameIsOver();
    }

    private function gameIsOver()
    {
        return array_reduce($this->players, function ($result, $player) {
            return $result || $player->getCoins() == self::GOLD_COINS_TO_WIN;
        }, false);
    }

    private function getCurrentPlayer()
    {
        return $this->players[$this->currentPlayerId];
    }
}
