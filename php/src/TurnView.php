<?php

namespace App;

class TurnView
{
    private $turn;

    public function __construct(Turn $turn)
    {
        $this->turn = $turn;
    }

    public function echoln($string)
    {
        echo $string . "\n";
    }
}
