<?php

namespace App;

use App\Player;

class Hit
{
    public Player|null $attackedPlayer;

    public Player|null $attackerPlayer;

    public int $damage;

    function __construct(Player|null $attackerPlayer, Player|null $attackedPlayer, int $damage)
    {
        $this->attackedPlayer = $attackedPlayer;
        $this->attackerPlayer = $attackerPlayer;
        $this->damage = $damage;
    }
}