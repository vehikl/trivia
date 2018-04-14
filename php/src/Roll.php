<?php

class Roll
{
    private $value;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function isOdd()
    {
        return $this->getValue() % 2 != 0;
    }

    public function isEven()
    {
        return !$this->isOdd();
    }

    public function getValue()
    {
        return $this->value;
    }
}
