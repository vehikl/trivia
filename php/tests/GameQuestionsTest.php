<?php

use App\GameQuestions;
use PHPUnit\Framework\TestCase;

class GameQuestionsTest extends TestCase
{
    public function testGameQuestionsInstantiates()
    {
        $questions = new GameQuestions(['Taco', 'Salad'], 10);
        $this->assertEquals($questions->askFrom('Taco'), "Taco Question 0");
    }
}
