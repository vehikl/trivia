<?php

class Game
{
    private $players;
    private $places;
    private $purses;
    private $inPenaltyBox;

    private $popQuestions;
    private $scienceQuestions;
    private $sportsQuestions;
    private $rockQuestions;

    private $currentPlayer = 0;
    private $isGettingOutOfPenaltyBox;

    const QUESTIONS_PER_CATEGORY = 50;

    const TOTAL_PLACES = 12;
    const LAST_PLACE = 11;

    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {

        $this->players = array();
        $this->places = array(0);
        $this->purses = array(0);
        $this->inPenaltyBox = array(0);

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
        array_push($this->players, $playerName);
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . count($this->players));
        return true;
    }

    private function howManyPlayers()
    {
        return count($this->players);
    }

    public function roll($roll)
    {
        echoln($this->getCurrentPlayerName() . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($this->getCurrentPlayerIsInPenaltyBox()) {
            if ($roll % 2 != 0) {
                $this->isGettingOutOfPenaltyBox = true;

                echoln($this->getCurrentPlayerName() . " is getting out of the penalty box");
                $this->movePlayer($roll);
            } else {
                echoln($this->getCurrentPlayerName() . " is not getting out of the penalty box");
                $this->isGettingOutOfPenaltyBox = false;
            }
        } else {
            $this->movePlayer($roll);
        }
    }

    private function movePlayer($roll)
    {
        $this->setCurrentPlayerSpace($this->getCurrentPlayerSpace() + $roll);
        if ($this->getCurrentPlayerSpace() > self::LAST_PLACE) {
            $this->setCurrentPlayerSpace($this->getCurrentPlayerSpace() - self::TOTAL_PLACES);
        }

        echoln($this->getCurrentPlayerName()
            . "'s new location is "
            . $this->getCurrentPlayerSpace());
        echoln("The category is " . $this->currentCategory());
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
        if ($this->getCurrentPlayerSpace() == 0) {
            return "Pop";
        }

        if ($this->getCurrentPlayerSpace() == 4) {
            return "Pop";
        }

        if ($this->getCurrentPlayerSpace() == 8) {
            return "Pop";
        }

        if ($this->getCurrentPlayerSpace() == 1) {
            return "Science";
        }

        if ($this->getCurrentPlayerSpace() == 5) {
            return "Science";
        }

        if ($this->getCurrentPlayerSpace() == 9) {
            return "Science";
        }

        if ($this->getCurrentPlayerSpace() == 2) {
            return "Sports";
        }

        if ($this->getCurrentPlayerSpace() == 6) {
            return "Sports";
        }

        if ($this->getCurrentPlayerSpace() == 10) {
            return "Sports";
        }

        return "Rock";
    }

    public function wasCorrectlyAnswered()
    {
        if ($this->getCurrentPlayerIsInPenaltyBox()) {
            if ($this->isGettingOutOfPenaltyBox) {
                echoln("Answer was correct!!!!");
                return $this->givePlayerGoldCoin();
            } else {
                $this->passTheDice();

                return true;
            }
        } else {
            echoln("Answer was corrent!!!!");
            return $this->givePlayerGoldCoin();
        }
    }

    private function givePlayerGoldCoin()
    {
        $this->addCoinToCurrentPlayer();
        echoln($this->getCurrentPlayerName() . " now has " . $this->getCurrentPlayerCoins() . " Gold Coins.");

        $winner = $this->didPlayerWin();
        $this->passTheDice();

        return $winner;
    }

    public function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->getCurrentPlayerName() . " was sent to the penalty box");
        $this->setCurrentPlayerIsInPenaltyBox(true);

        $this->passTheDice();

        return true;
    }

    private function passTheDice()
    {
        $this->currentPlayer++;
        if ($this->getCurrentPlayerId() == count($this->players)) {
            $this->currentPlayer = 0;
        }
    }

    private function didPlayerWin()
    {
        return !($this->getCurrentPlayerCoins() == self::GOLD_COINS_TO_WIN);
    }

    private function getCurrentPlayerId()
    {
        return $this->currentPlayer;
    }

    private function getCurrentPlayerName()
    {
        return $this->players[$this->getCurrentPlayerId()];
    }

    private function getCurrentPlayerCoins()
    {
        return $this->purses[$this->getCurrentPlayerId()];
    }

    private function addCoinToCurrentPlayer()
    {
        $this->purses[$this->getCurrentPlayerId()]++;
    }

    private function setCurrentPlayerSpace($value)
    {
        $this->places[$this->getCurrentPlayerId()] = $value;
    }

    private function getCurrentPlayerSpace()
    {
        return $this->places[$this->getCurrentPlayerId()];
    }

    private function setCurrentPlayerIsInPenaltyBox($value)
    {
        $this->inPenaltyBox[$this->getCurrentPlayerId()] = $value;
    }

    private function getCurrentPlayerIsInPenaltyBox()
    {
        return $this->inPenaltyBox[$this->getCurrentPlayerId()];
    }
}
