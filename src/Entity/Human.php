<?php

namespace App\Entity;

use App\Player;
use App\PlayerType;

class Human extends Player
{
    function __construct($startingHp)
    {
        parent::__construct(PlayerType::HUMAN, $startingHp);
    }

    function getDamage(Player $attacker): int
    {
        return match ($attacker->getType()) {
            PlayerType::QUEEN_BEE => 10,
            PlayerType::WORKER_BEE => 5,
            PlayerType::DRONE_BEE => 1,
            default => throw new \RuntimeException('Human cannot be hit by ' . $attacker->getType()),
        };
    }
}