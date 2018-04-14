<?php

namespace App;

class GameBoard
{
    private $places;

    public function __construct($categories, $numberOfPlaces)
    {
        for ($i = 0; $i < $numberOfPlaces; $i++) {
            $categoryId = $i % count($categories);
            $this->places[] = new Place($i, $categories[$categoryId]);
        }
    }

    public function findPlace($location)
    {
        return $this->places[$location];
    }

    public function firstPlace()
    {
        return $this->findPlace(0);
    }

    public function findPlaceNumberOfPlacesFromCurrentPlace(Place $currentPlace, $numberOfPlacesToMove)
    {
        $location = $currentPlace->getLocation() + $numberOfPlacesToMove;
        $location = $this->placeLoops($location) ? $location - $this->getTotalNumberOfPlaces() : $location;

        return $this->findPlace($location);
    }

    private function placeLoops($place)
    {
        return $place >= $this->getTotalNumberOfPlaces();
    }

    private function getTotalNumberOfPlaces()
    {
        return count($this->places);
    }
}
