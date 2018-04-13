<?php

class Game
{
    private $players;
    private $currentPlayerId = 0;

    private $popQuestions;
    private $scienceQuestions;
    private $sportsQuestions;
    private $rockQuestions;

    const QUESTIONS_PER_CATEGORY = 50;

    const TOTAL_PLACES = 12;
    const LAST_PLACE = 11;

    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {

        $this->players = array();

        $this->popQuestions = array();
        $this->scienceQuestions = array();
        $this->sportsQuestions = array();
        $this->rockQuestions = array();

        for ($i = 0; $i < self::QUESTIONS_PER_CATEGORY; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, "Science Question " . $i);
            array_push($this->sportsQuestions, "Sports Question " . $i);
            array_push($this->rockQuestions, "Rock Question " . $i);
        }
    }

    public function add($playerName)
    {
        $this->players[] = new Player($playerName);

        echoln("{$playerName} was added");
        echoln("They are player number {$this->howManyPlayers()}");
        return true;
    }

    private function howManyPlayers()
    {
        return count($this->players);
    }

    public function roll($roll)
    {
        echoln("{$this->getCurrentPlayer()->getName()} is the current player");
        echoln("They have rolled a {$roll}");

        if ($this->getCurrentPlayer()->getIsInPenaltyBox() && $this->rolledOdds($roll)) {
            $this->getCurrentPlayer()->setIsGettingOutOfPenaltyBox(true);
            echoln("{$this->getCurrentPlayer()->getName()} is getting out of the penalty box");
            $this->movePlayer($roll);
        } else if ($this->getCurrentPlayer()->getIsInPenaltyBox()) {
            echoln("{$this->getCurrentPlayer()->getName()} is not getting out of the penalty box");
            $this->getCurrentPlayer()->setIsGettingOutOfPenaltyBox(false);
        } else {
            $this->movePlayer($roll);
        }
    }

    private function rolledOdds($roll)
    {
        return $roll % 2 != 0;
    }

    private function movePlayer($roll)
    {
        $this->getCurrentPlayer()->setSpace($this->getCurrentPlayer()->getSpace() + $roll);
        if ($this->getCurrentPlayer()->getSpace() > self::LAST_PLACE) {
            $this->getCurrentPlayer()->setSpace($this->getCurrentPlayer()->getSpace() - self::TOTAL_PLACES);
        }

        echoln("{$this->getCurrentPlayer()->getName()}'s new location is {$this->getCurrentPlayer()->getSpace()}");
        echoln("The category is {$this->currentCategory()}");
        $this->askQuestion();
    }

    private function askQuestion()
    {
        if ($this->currentCategory() == "Pop") {
            echoln(array_shift($this->popQuestions));
        }

        if ($this->currentCategory() == "Science") {
            echoln(array_shift($this->scienceQuestions));
        }

        if ($this->currentCategory() == "Sports") {
            echoln(array_shift($this->sportsQuestions));
        }

        if ($this->currentCategory() == "Rock") {
            echoln(array_shift($this->rockQuestions));
        }
    }

    private function currentCategory()
    {
        if ($this->getCurrentPlayer()->getSpace() == 0) {
            return "Pop";
        }

        if ($this->getCurrentPlayer()->getSpace() == 4) {
            return "Pop";
        }

        if ($this->getCurrentPlayer()->getSpace() == 8) {
            return "Pop";
        }

        if ($this->getCurrentPlayer()->getSpace() == 1) {
            return "Science";
        }

        if ($this->getCurrentPlayer()->getSpace() == 5) {
            return "Science";
        }

        if ($this->getCurrentPlayer()->getSpace() == 9) {
            return "Science";
        }

        if ($this->getCurrentPlayer()->getSpace() == 2) {
            return "Sports";
        }

        if ($this->getCurrentPlayer()->getSpace() == 6) {
            return "Sports";
        }

        if ($this->getCurrentPlayer()->getSpace() == 10) {
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
            return $gameIsNotOver = true;
        } else {
            echoln("Answer was corrent!!!!");
            return $this->givePlayerGoldCoin();
        }
    }

    private function givePlayerGoldCoin()
    {
        $this->getCurrentPlayer()->addCoin();
        echoln("{$this->getCurrentPlayer()->getName()} now has {$this->getCurrentPlayer()->getCoins()} Gold Coins.");

        $gameIsNotOver = $this->didPlayerWin();
        $this->passTheDice();

        return $gameIsNotOver;
    }

    public function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln("{$this->getCurrentPlayer()->getName()} was sent to the penalty box");
        $this->getCurrentPlayer()->setIsInPenaltyBox(true);

        $this->passTheDice();

        return $gameIsNotOver = true;
    }

    private function passTheDice()
    {
        $this->currentPlayerId++;
        if ($this->currentPlayerId == count($this->players)) {
            $this->currentPlayerId = 0;
        }
    }

    private function didPlayerWin()
    {
        return !($this->getCurrentPlayer()->getCoins() == self::GOLD_COINS_TO_WIN);
    }

    private function getCurrentPlayer()
    {
        return $this->players[$this->currentPlayerId];
    }
}
