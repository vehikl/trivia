<?php

namespace App;

class GameQuestions
{
    private $questions;

    public function __construct($categories)
    {
        $this->questions = $this->generateCategoriesQuestions($categories);
    }

    private function generateCategoriesQuestions($categories)
    {
        return array_combine($categories, array_map([$this, 'createCategoryQuestionsFor'], $categories));
    }

    private function createCategoryQuestionsFor($category)
    {
        return new CategoryQuestions($category);
    }

    public function askFrom($category)
    {
        return $this->questions[$category]->ask();
    }
}
