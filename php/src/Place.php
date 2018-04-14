<?php

namespace App;

class Place
{
    private $location;
    private $category;

    public function __construct($location, $category)
    {
        $this->location = $location;
        $this->category = $category;
    }

    public function getLocation()
    {
        return $this->location;
    }

    public function getCategory()
    {
        return $this->category;
    }
}
