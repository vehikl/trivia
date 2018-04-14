<?php

use App\GameBoard;
use App\Place;
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
        $currentPlace = new Place(1, "Taco");
        $numberOfPlaces = 2;
        $newPlace = $this->board->findPlaceNumberOfPlacesFromCurrentPlace($currentPlace, $numberOfPlaces);
        $this->assertEquals(3, $newPlace->getLocation());
    }

    public function testMovingSomeNumberOfPlacesThatLoops()
    {
        $currentPlace = new Place(11, "Taco");
        $numberOfPlaces = 4;
        $newPlace = $this->board->findPlaceNumberOfPlacesFromCurrentPlace($currentPlace, $numberOfPlaces);
        $this->assertEquals(3, $newPlace->getLocation());
    }
}
