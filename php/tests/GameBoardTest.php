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
        $this->assertEquals($this->board->getPlace(2)->getCategory(), "Taco");
    }

    public function testMovingSomeNumberOfPlaces()
    {
        $currentPlace = 1;
        $numberOfPlaces = 2;
        $newPlace = $this->board->findPlaceNumberOfPlacesFromCurrentPlace($currentPlace, $numberOfPlaces);
        $this->assertEquals(3, $newPlace);
    }

    public function testMovingSomeNumberOfPlacesThatLoops()
    {
        $currentPlace = 11;
        $numberOfPlaces = 4;
        $newPlace = $this->board->findPlaceNumberOfPlacesFromCurrentPlace($currentPlace, $numberOfPlaces);
        $this->assertEquals(3, $newPlace);
    }
}
