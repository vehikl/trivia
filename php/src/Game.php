<?php

class Game
{
    private $players = [];
    private $places = [0];
    private $purses = [0];
    private $inPenaltyBox = [0];
    private $popQuestions = [];
    private $scienceQuestions = [];
    private $sportsQuestions = [];
    private $rockQuestions = [];
    private $currentPlayer = 0;
    private $isGettingOutOfPenaltyBox;
    const MAX_PLACES = 12;
    const WINNING_COIN_COUNT = 6;

    function __construct()
    {
        for ($i = 0; $i < 50; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, ("Science Question " . $i));
            array_push($this->sportsQuestions, ("Sports Question " . $i));
            array_push($this->rockQuestions, $this->createRockQuestion($i));
        }
    }

    function createRockQuestion($index)
    {
        return "Rock Question " . $index;
    }

    function isPlayable()
    {
        return ($this->howManyPlayers() >= 2);
    }

    function add($playerName)
    {
        array_push($this->players, $playerName);
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . count($this->players));
        return true;
    }

    function howManyPlayers()
    {
        return count($this->players);
    }

    function roll($roll)
    {
        echoln($this->players[$this->currentPlayer] . " is the current player");
        echoln("They have rolled a " . $roll);

        if (! $this->inPenaltyBox[$this->currentPlayer]) {
            $this->doTurn($roll);
            return;
        }

        if ($this->canGetOutOfPenaltyBox($roll)) {
            $this->isGettingOutOfPenaltyBox = true;

            echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
            $this->doTurn($roll);
            return;
        }

        echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
        $this->isGettingOutOfPenaltyBox = false;
    }

    private function canGetOutOfPenaltyBox($roll)
    {
        return $roll % 2 != 0;
    }

    function askQuestion()
    {
        $var = strtolower($this->currentCategory()) . "Questions";

        if (! isset($this->$var)) {
            return null;
        }

        echoln(array_shift($this->{$var}));
    }


    function currentCategory()
    {
        if ($this->places[$this->currentPlayer] == 0) return "Pop";
        if ($this->places[$this->currentPlayer] == 4) return "Pop";
        if ($this->places[$this->currentPlayer] == 8) return "Pop";
        if ($this->places[$this->currentPlayer] == 1) return "Science";
        if ($this->places[$this->currentPlayer] == 5) return "Science";
        if ($this->places[$this->currentPlayer] == 9) return "Science";
        if ($this->places[$this->currentPlayer] == 2) return "Sports";
        if ($this->places[$this->currentPlayer] == 6) return "Sports";
        if ($this->places[$this->currentPlayer] == 10) return "Sports";
        return "Rock";
    }

    function correctAnswer()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                echoln("Answer was correct!!!!");
                return $this->giveCoins();
            } else {
                $this->nextPlayer();
                return true;
            }
        } else {
            echoln("Answer was corrent!!!!");
            return $this->giveCoins();
        }
    }

    function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;
        $this->nextPlayer();
        return true;
    }


    function didPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == self::WINNING_COIN_COUNT);
    }

    /**
     * Do a turn
     *
     * @param $roll
     */
    private function doTurn($roll)
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
        if ($this->places[$this->currentPlayer] > self::MAX_PLACES - 1) {
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - self::MAX_PLACES;
        }
        echoln($this->players[$this->currentPlayer] . "'s new location is " . $this->places[$this->currentPlayer]);
        echoln("The category is " . $this->currentCategory());
        $this->askQuestion();
    }

    /**
     * @return bool
     */
    private function giveCoins()
    {
        $this->purses[$this->currentPlayer]++;
        echoln($this->players[$this->currentPlayer] . " now has " . $this->purses[$this->currentPlayer] . " Gold Coins.");
        $winner = $this->didPlayerWin();
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }
        return $winner;
    }

    private function nextPlayer()
    {
        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players)) {
            $this->currentPlayer = 0;
        }
    }
}
