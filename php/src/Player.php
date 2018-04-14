<?php

namespace App;

class Player
{
    private $name;
    private $place;
    private $purse;
    private $inPenaltyBox;

    public function __construct($name, Place $place)
    {
        $this->name = $name;
        $this->place = $place;
        $this->purse = 0;
        $this->inPenaltyBox = 0;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getCoins()
    {
        return $this->purse;
    }

    public function addCoin()
    {
        $this->purse++;
    }

    public function moveTo($place)
    {
        $this->place = $place;
    }

    public function getPlace()
    {
        return $this->place;
    }

    public function receivePenalty()
    {
        $this->inPenaltyBox = true;
    }

    public function isInPenaltyBox()
    {
        return $this->inPenaltyBox;
    }
}
