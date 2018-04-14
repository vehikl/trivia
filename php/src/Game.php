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
        $player = $this->getCurrentPlayer();
        $player->setRoll($roll);

        echoln("{$player->getName()} is the current player");
        echoln("They have rolled a {$roll->getValue()}");

        if ($player->isNotAllowedToMove()) {
            echoln("{$player->getName()} is not getting out of the penalty box");
            return;
        }

        if ($player->isInPenaltyBox()) {
            echoln("{$player->getName()} is getting out of the penalty box");
        }

        $this->movePlayer($roll->getValue());
        $this->askQuestion();
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
        return $this->correctAnswer();
    }

    public function correctAnswer()
    {
        if ($this->getCurrentPlayer()->isAllowedToAnswer()) {
            $correct = $this->getCurrentPlayer()->isNotInPenaltyBox() ? 'corrent' : 'correct';
            echoln("Answer was {$correct}!!!!");
            $this->givePlayerGoldCoin();
        }

        $this->passTheDice();
        return $this->gameIsNotOver();
    }

    public function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        $this->sendPlayerToPenaltyBox();

        $this->passTheDice();
        return $this->gameIsNotOver();
    }

    private function givePlayerGoldCoin()
    {
        $this->getCurrentPlayer()->addCoin();
        echoln("{$this->getCurrentPlayer()->getName()} now has {$this->getCurrentPlayer()->getCoins()} Gold Coins.");
    }

    private function sendPlayerToPenaltyBox()
    {
        $this->getCurrentPlayer()->receivePenalty();
        echoln("{$this->getCurrentPlayer()->getName()} was sent to the penalty box");
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
        return !array_reduce($this->players, function ($result, $player) {
            return $result || $player->getCoins() == self::GOLD_COINS_TO_WIN;
        }, false);
    }

    private function getCurrentPlayer()
    {
        return $this->players[$this->currentPlayerId];
    }
}
