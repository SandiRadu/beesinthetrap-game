<?php

namespace App\Entity;

use App\Player;
use App\PlayerType;

/**
 * This class represents a Bee, which can have one of the following types:
 * - PlayerType::QUEEN_BEE
 * - PlayerType::WORKER_BEE
 * - PlayerType::DRONE_BEE
 * 
 * It also requires a starting HP and the damage that it's suffering when it is attacked by another player. This damage will be substracted from the Bee's HP on each hit.
 */
class Bee extends Player
{
    private int $damage;

    function __construct(string $type, int $startingHp, int $damage)
    {
        parent::__construct($type, $startingHp);
        if (!in_array($type, [PlayerType::QUEEN_BEE, PlayerType::DRONE_BEE, PlayerType::WORKER_BEE])) {
            throw new \InvalidArgumentException("A bee cannot have type " . $type);
        }
        $this->damage = $damage;
    }

    function getDamage(Player $attacker): int
    {
        return $this->damage;
    }

}