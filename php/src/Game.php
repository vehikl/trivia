<?php

namespace App;

class Game
{
    private $players = [];
    private $currentPlayerId = 0;
    private $board;
    private $questions;
    private $turn;

    private $view;

    const CATEGORIES = ["Pop", "Science", "Sports", "Rock"];
    const NUMBER_OF_PLACES = 12;
    const QUESTIONS_PER_CATEGORY = 50;
    const GOLD_COINS_TO_WIN = 6;

    public function __construct()
    {
        $this->board = new GameBoard(self::CATEGORIES, self::NUMBER_OF_PLACES);
        $this->questions = new GameQuestions(self::CATEGORIES, self::QUESTIONS_PER_CATEGORY);
        $this->view = new GameView();
    }

    public function add($playerName)
    {
        $this->players[] = new Player($playerName, $this->board->firstPlace());
        $this->view->displayPlayerIsAdded($playerName, $this->howManyPlayers());
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

    public function wasCorrectlyAnswered()
    {
        $this->turn->correctAnswer();
        return $this->passTheDice();
    }

    public function wrongAnswer()
    {
        $this->turn->wrongAnswer();
        return $this->passTheDice();
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

    public function getQuestions()
    {
        return $this->questions;
    }
}
