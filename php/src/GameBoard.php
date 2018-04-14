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

    public function getPlace($placeId)
    {
        return $this->places[$placeId];
    }

    public function findPlaceNumberOfPlacesFromCurrentPlace($currentPlace, $numberOfPlaces)
    {
        $place = $currentPlace + $numberOfPlaces;

        return $this->placeLoops($place) ? $place - $this->getTotalNumberOfPlaces() : $place;
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
