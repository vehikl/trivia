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

    private const Categories = ['Pop', 'Science', 'Sports', 'Rock'];

    public function __construct()
    {

        $this->players = [];
        $this->places = [0];
        $this->purses = [0];
        $this->inPenaltyBox = [0];

        $this->popQuestions = [];
        $this->scienceQuestions = [];
        $this->sportsQuestions = [];
        $this->rockQuestions = [];

        for ($i = 0; $i < 50; $i++) {
            array_push($this->popQuestions, "Pop Question " . $i);
            array_push($this->scienceQuestions, ("Science Question " . $i));
            array_push($this->sportsQuestions, ("Sports Question " . $i));
            array_push($this->rockQuestions, $this->createRockQuestion($i));
        }
    }

    private function createRockQuestion($index)
    {

        return "Rock Question " . $index;
    }

    private function isPlayable()
    {

        return ($this->howManyPlayers() >= 2);
    }

    public function add($playerName)
    {

        $this->players[] = $playerName;
        $this->places[$this->howManyPlayers()] = 0;
        $this->purses[$this->howManyPlayers()] = 0;
        $this->inPenaltyBox[$this->howManyPlayers()] = false;

        echoln($playerName . " was added");
        echoln("They are player number " . $this->howManyPlayers());
        return true;
    }

    private function howManyPlayers()
    {

        return count($this->players);
    }

    public function roll($roll)
    {

        echoln($this->players[$this->currentPlayer] . " is the current player");
        echoln("They have rolled a " . $roll);

        if ($this->isPlayerInThePenaltyBox()) {

            $this->isGettingOutOfPenaltyBox = $this->isOddRoll($roll);
            if (! $this->isGettingOutOfPenaltyBox) {
                echoln($this->players[$this->currentPlayer] . " is not getting out of the penalty box");
                return;
            }

            echoln($this->players[$this->currentPlayer] . " is getting out of the penalty box");
        }

        $this->moveThePlayer($roll);
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

        return self::Categories[$this->playersCurrentPlace() % 4];
    }

    public function wasCorrectlyAnswered()
    {

        if ($this->isPlayerInThePenaltyBox() && ! $this->isGettingOutOfPenaltyBox) {
            return $this->endTurn();
        }

        $correct = $this->isPlayerInThePenaltyBox() && $this->isGettingOutOfPenaltyBox ? 'correct' : 'corrent';

        echoln("Answer was {$correct}!!!!");

        $this->payThePlayer();

        return $this->endTurn();

    }

    public function wrongAnswer()
    {

        echoln("Question was incorrectly answered");
        $this->putThePlayerInThePenaltyBox();

        return $this->endTurn();
    }

    public function gameIsNotOver() : bool
    {

        return ! array_reduce($this->purses, function (bool $hasWinner, int $purse) : bool {

            return $hasWinner || $purse == 6;
        }, false);
    }

    private function passTheDice() : void
    {

        $this->currentPlayer++;
        if ($this->currentPlayer == $this->howManyPlayers()) {
            $this->currentPlayer = 0;
        }
    }

    private function payThePlayer() : void
    {

        $this->purses[$this->currentPlayer]++;
        echoln($this->players[$this->currentPlayer]
               . " now has "
               . $this->purses[$this->currentPlayer]
               . " Gold Coins.");
    }

    /**
     * @return bool
     */
    private function endTurn() : bool
    {

        $this->passTheDice();

        return $this->gameIsNotOver();
    }

    private function putThePlayerInThePenaltyBox() : void
    {

        echoln($this->players[$this->currentPlayer] . " was sent to the penalty box");
        $this->inPenaltyBox[$this->currentPlayer] = true;
    }

    private function playersCurrentPlace() : int
    {

        return $this->places[$this->currentPlayer];
    }

    private function setPlayersCurrentPlace($place)
    {

        $this->places[$this->currentPlayer] = $place;
    }

    /**
     * @param $roll
     */
    private function moveThePlayer($roll) : void
    {

        $this->setPlayersCurrentPlace($this->playersCurrentPlace() + $roll);
        if ($this->playersCurrentPlace() > 11) {
            $this->setPlayersCurrentPlace($this->playersCurrentPlace() - 12);
        }

        echoln($this->players[$this->currentPlayer]
               . "'s new location is "
               . $this->playersCurrentPlace());
        echoln("The category is " . $this->currentCategory());
    }

    private function isOddRoll($roll) : bool
    {

        return $roll % 2 != 0;
    }

    private function isPlayerInThePenaltyBox() : bool
    {

        return (bool) $this->inPenaltyBox[$this->currentPlayer];
    }
}
