<?php

namespace App\Entity;

use App\Player;
use App\PlayerType;

/**
 * The `Bee` class extends the abstract `Player` class, representing a bee player in the game:
 * - PlayerType::QUEEN_BEE
 * - PlayerType::WORKER_BEE
 * - PlayerType::DRONE_BEE
 * 
 * It also requires a starting HP and the damage that it's suffering when it is attacked by another player. This damage will be substracted from the Bee's HP on each hit.
 */
class Bee extends Player
{
    /**
     * @var int $damage The amount of damage the bee inflicts during an attack.
     */
    private int $damage;

    /**
     * Bee constructor.
     *
     * @param string $type The type of the bee (QUEEN_BEE, WORKER_BEE, DRONE_BEE).
     * @param int $startingHp The initial health points of the bee.
     * @param int $damage The damage inflicted by the bee during an attack.
     *
     * @throws \InvalidArgumentException If an invalid type is provided for the bee.
     */
    public function __construct(string $type, int $startingHp, int $damage)
    {
        parent::__construct($type, $startingHp);

        if (!in_array($type, [PlayerType::QUEEN_BEE, PlayerType::DRONE_BEE, PlayerType::WORKER_BEE])) {
            throw new \InvalidArgumentException("A bee cannot have type " . $type);
        }

        $this->damage = $damage;
    }

    /**
     * Returns the fixed amount of damage inflicted by the bee during an attack.
     *
     * @param Player $attacker The player performing the attack (unused in bee's damage calculation).
     *
     * @return int The amount of damage inflicted by the bee.
     */
    public function getDamage(Player $attacker): int
    {
        return $this->damage;
    }
}