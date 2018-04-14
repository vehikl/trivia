<?php

namespace App;

class GameView extends View
{
    public function displayPlayerIsAdded($playerName, $totalPlayers)
    {
        $this->echoln("{$playerName} was added");
        $this->echoln("They are player number {$totalPlayers}");
    }
}
