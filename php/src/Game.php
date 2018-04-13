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

    function __construct()
    {

        $this->players      = [];
        $this->places       = [0];
        $this->purses       = [0];
        $this->inPenaltyBox = [0];

        $this->generateQuestions();
    }

    function isPlayable()
    {
        return ($this->howManyPlayers() >= 2);
    }

    function add($playerName)
    {
        array_push($this->players, $playerName);
        $this->places[$this->howManyPlayers()]       = 0;
        $this->purses[$this->howManyPlayers()]       = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . $this->howManyPlayers());
    }

    function howManyPlayers()
    {
        return count($this->players);
    }

    function roll($roll)
    {
        echoln($this->players[$this->currentPlayer] . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($roll % 2 == 0) {
                $this->penaltyBoxAction(false);

                return;
            }
            $this->penaltyBoxAction(true);
        }

        $this->movePlayer($roll);
    }

    function askQuestion()
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


    function currentCategory()
    {
        if (in_array($this->places[$this->currentPlayer], [0, 4, 8])) {
            return "Pop";
        }
        if (in_array($this->places[$this->currentPlayer], [1, 5, 9])) {
            return "Science";
        }
        if (in_array($this->places[$this->currentPlayer], [2, 6, 10])) {
            return "Sports";
        }
        return "Rock";
    }

    function wasCorrectlyAnswered()
    {
        if ($this->inPenaltyBox[$this->currentPlayer]) {
            if ($this->isGettingOutOfPenaltyBox) {
                echoln("Answer was correct!!!!");
                $this->purses[$this->currentPlayer]++;
                echoln($this->players[$this->currentPlayer]
                    . " now has "
                    . $this->purses[$this->currentPlayer]
                    . " Gold Coins.");

                $winner = $this->didPlayerWin();
                $this->currentPlayer++;
                if ($this->currentPlayer == count($this->players))
                    $this->currentPlayer = 0;

                return $winner;
            } else {
                $this->currentPlayer++;
                if ($this->currentPlayer == count($this->players))
                    $this->currentPlayer = 0;

                return true;
            }


        } else {

            echoln("Answer was corrent!!!!");
            $this->purses[$this->currentPlayer]++;
            echoln($this->players[$this->currentPlayer]
                . " now has "
                . $this->purses[$this->currentPlayer]
                . " Gold Coins.");

            $winner = $this->didPlayerWin();
            $this->currentPlayer++;
            if ($this->currentPlayer == count($this->players))
                $this->currentPlayer = 0;

            return $winner;
        }
    }

    function wrongAnswer()
    {
        echoln("Question was incorrectly answered");
        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;

        $this->currentPlayer++;
        if ($this->currentPlayer == count($this->players))
            $this->currentPlayer = 0;

        return true;
    }


    function didPlayerWin()
    {
        return !($this->purses[$this->currentPlayer] == 6);
    }

    public function generateQuestions($numberOfQuestions = 50)
    {
        foreach (['Pop', 'Science', 'Sports', 'Rock'] as $category) {
            $tmp          = strtolower($category) . 'Questions';
            $this->{$tmp} = $this->generateCategoryQuestions($category, $numberOfQuestions);
        }
    }

    public function generateCategoryQuestions($category, $numberOfQuestions = 50)
    {
        $result = [];
        for ($i = 0; $i < $numberOfQuestions; $i++) {
            $result[] = $this->createQuestion($category, $i);
        }

        return $result;
    }

    private function createQuestion($category, $index)
    {
        return $category . " Question " . $index;
    }

    /**
     * @param $roll
     */
    public function movePlayer($roll)
    {
        $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] + $roll;
        if ($this->places[$this->currentPlayer] > 11)
            $this->places[$this->currentPlayer] = $this->places[$this->currentPlayer] - 12;

        echoln($this->players[$this->currentPlayer]
            . "'s new location is "
            . $this->places[$this->currentPlayer]);
        echoln("The category is " . $this->currentCategory());
        $this->askQuestion();
    }

    /**
     * @param $isGettingOut
     */
    public function penaltyBoxAction($isGettingOut)
    {
        $not = $isGettingOut ? '' : 'not ';
        echoln($this->players[$this->currentPlayer] . " is {$not}getting out of the penalty box");
        $this->isGettingOutOfPenaltyBox = $isGettingOut;
    }
}
