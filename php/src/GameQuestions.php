<?php

namespace App;

class GameQuestions
{
    private $questions;

    public function __construct($categories, $questionsPerCategory)
    {
        $this->questions = array_combine($categories, array_map(function ($name) use ($questionsPerCategory) {
            return new CategoryQuestions($name, $questionsPerCategory);
        }, $categories));
    }

    public function askFrom($category)
    {
        return $this->questions[$category]->ask();
    }
}
