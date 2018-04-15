<?php

namespace App;

class GameBoard
{
    private $places;
    private $categories;

    public function __construct($categories, $numberOfPlaces)
    {
        $this->categories = $categories;
        $this->places = $this->generatePlaces($numberOfPlaces);
    }

    private function generatePlaces($numberOfPlaces)
    {
        $locations = range(0, $numberOfPlaces - 1);
        return array_map([$this, 'createPlaceAt'], $locations);
    }

    private function createPlaceAt($location)
    {
        $categoryId = $location % count($this->categories);
        return new Place($location, $this->categories[$categoryId]);
    }

    public function findPlace($location)
    {
        return $this->places[$location];
    }

    public function firstPlace()
    {
        return $this->findPlace(0);
    }

    public function findPlaceNumberOfPlacesFrom(Place $currentPlace, $numberOfPlacesToMove)
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
