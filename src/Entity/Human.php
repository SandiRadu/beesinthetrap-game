<?php

namespace App\Entity;

use App\Player;
use App\PlayerType;

/**
 * Class Human
 *
 * The `Human` class extends the abstract `Player` class, representing a human player in the game. It provides specific
 * implementations for the human player type, including the constructor and the method to calculate damage inflicted by other players.
 *
 * 
 */
class Human extends Player
{
    /**
     * Human constructor.
     *
     * @param int $startingHp The initial health points of the human player.
     */
    public function __construct(int $startingHp)
    {
        parent::__construct(PlayerType::HUMAN, $startingHp);
    }

    /**
     * Calculates and returns the damage inflicted by the specified attacker.
     *
     * @param Player $attacker The player performing the attack.
     *
     * @return int The amount of damage inflicted.
     * @throws \RuntimeException If the attacker type is not recognized.
     */
    public function getDamage(Player $attacker): int
    {
        return match ($attacker->getType()) {
            PlayerType::QUEEN_BEE => 10,
            PlayerType::WORKER_BEE => 5,
            PlayerType::DRONE_BEE => 1,
            default => throw new \RuntimeException('Human cannot be hit by ' . $attacker->getType()),
        };
    }
}
