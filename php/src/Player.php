<?php

class Player
{
    private $name;
    private $place;
    private $purse;
    private $inPenaltyBox;
    private $isGettingOutOfPenaltyBox;

    public function __construct($name)
    {
        $this->name = $name;
        $this->place = 0;
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

    public function setSpace($value)
    {
        $this->place = $value;
    }

    public function getSpace()
    {
        return $this->place;
    }

    public function setIsInPenaltyBox($value)
    {
        $this->inPenaltyBox = $value;
    }

    public function getIsInPenaltyBox()
    {
        return $this->inPenaltyBox;
    }

    public function setIsGettingOutOfPenaltyBox($value)
    {
        $this->isGettingOutOfPenaltyBox = $value;
    }

    public function getIsGettingOutOfPenaltyBox()
    {
        return $this->isGettingOutOfPenaltyBox;
    }
}
