<?php

namespace App;

class Place
{
    private $position;
    private $category;

    public function __construct($position, $category)
    {
        $this->position = $position;
        $this->category = $category;
    }

    public function getPosition()
    {
        return $this->position;
    }

    public function getCategory()
    {
        return $this->category;
    }
}
