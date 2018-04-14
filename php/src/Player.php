<?php

namespace App;

class Player
{
    private $name;
    private $place;
    private $purse;
    private $roll;
    private $inPenaltyBox;
    private $isGettingOutOfPenaltyBox;

    public function __construct($name)
    {
        $this->name = $name;
        $this->place = 0;
        $this->purse = 0;
        $this->roll = 0;
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

    public function setSpace($value)
    {
        $this->place = $value;
    }

    public function getSpace()
    {
        return $this->place;
    }

    public function setRoll($value)
    {
        $this->roll = $value;
    }

    public function getRoll()
    {
        return $this->roll;
    }

    public function receivePenalty()
    {
        $this->inPenaltyBox = true;
    }

    public function isInPenaltyBox()
    {
        return $this->inPenaltyBox;
    }

    public function isNotInPenaltyBox()
    {
        return !$this->isInPenaltyBox();
    }

    public function isGettingOutOfPenaltyBox()
    {
        return $this->roll->isOdd();
    }

    public function isAllowedToMove()
    {
        return $this->isNotInPenaltyBox() || $this->roll->isOdd();
    }

    public function isNotAllowedToMove()
    {
        return $this->isInPenaltyBox() && $this->roll->isEven();
    }

    public function isAllowedToAnswer()
    {
        return $this->isNotInPenaltyBox() || $this->isGettingOutOfPenaltyBox();
    }

    public function isNotAllowedToAnswer()
    {
        return $this->isInPenaltyBox() && !$this->isGettingOutOfPenaltyBox();
    }
}
