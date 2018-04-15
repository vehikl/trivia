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

    public function __construct($view)
    {
        $this->board = new GameBoard(self::CATEGORIES, self::NUMBER_OF_PLACES);
        $this->questions = new GameQuestions(self::CATEGORIES, self::QUESTIONS_PER_CATEGORY);
        $this->view = $view;
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

    public function add($playerName)
    {
        $this->players[] = new Player($playerName, $this->board->firstPlace());
        $this->view->displayPlayerIsAdded($playerName, $this->howManyPlayers());
    }

    public function roll($rolledNumber)
    {
        $player = $this->getCurrentPlayer();
        $roll = new Roll($rolledNumber);
        $this->turn = new Turn($player, $roll, $this->board, $this->questions, $this->view);
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

    private function howManyPlayers()
    {
        return count($this->players);
    }

    private function passTheDice()
    {
        $nextId = $this->currentPlayerId + 1;
        $this->currentPlayerId = $nextId < $this->howManyPlayers() ? $nextId : 0;
        return $this->isContinuing();
    }

    private function isContinuing()
    {
        return !$this->isOver();
    }

    private function isOver()
    {
        return count(array_filter($this->players, [$this, 'isPlayerWinner'])) > 0;
    }

    private function isPlayerWinner(Player $player)
    {
        return $player->getCoins() == self::GOLD_COINS_TO_WIN;
    }
}
