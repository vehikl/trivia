<?php

namespace App;

class CategoryQuestions
{
    private $questions = [];

    public function __construct($name, $numberOfQuestions)
    {
        for ($i = 0; $i < $numberOfQuestions; $i++) {
            $this->questions[] = $this->generateQuestion($name, $i);
        }
    }

    private function generateQuestion($category, $number)
    {
        return "{$category} Question {$number}";
    }

    public function ask()
    {
        return array_shift($this->questions);
    }
}
