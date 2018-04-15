<?php

namespace App;

class CategoryQuestions
{
    private $name;
    private $index = -1;

    public function __construct($name, $numberOfQuestions)
    {
        $this->name = $name;
    }

    public function ask()
    {
        return "{$this->name} Question " . ++$this->index;
    }
}
