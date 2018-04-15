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
        $positions = range(0, $numberOfPlaces - 1);
        return array_map([$this, 'createPlaceAt'], $positions);
    }

    private function createPlaceAt($position)
    {
        $categoryId = $position % count($this->categories);
        return new Place($position, $this->categories[$categoryId]);
    }

    public function findPlace($position)
    {
        $position = $position % count($this->places) ?? $position;
        return $this->places[$position];
    }

    public function firstPlace()
    {
        return $this->findPlace(0);
    }
}
