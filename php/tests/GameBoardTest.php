<?php

use App\GameBoard;
use PHPUnit\Framework\TestCase;

class GameBoardTest extends TestCase
{
    private $board;

    public function setUp()
    {
        $this->board = new GameBoard(['Taco', 'Salad'], 12);
    }

    public function testGameBoardInstantiates()
    {
        $this->assertEquals($this->board->findPlace(2)->getCategory(), "Taco");
    }

    public function testMovingSomeNumberOfPlaces()
    {
        $position = 3;
        $place = $this->board->findPlace(3);
        $this->assertEquals(3, $place->getPosition());
    }

    public function testMovingSomeNumberOfPlacesThatLoops()
    {
        $position = 15;
        $place = $this->board->findPlace($position);
        $this->assertEquals(3, $place->getPosition());
    }
}
