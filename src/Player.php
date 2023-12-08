<?php

namespace App;

/**
 * Class Player
 *
 * The `Player` class is an abstract class representing a generic player in a game. It defines common properties and methods
 * that can be extended and implemented by concrete player classes.
 *
 *
 */
abstract class Player
{
    /**
     * @var string $type Represents the type or category of the player.
     */
    private string $type;

    /**
     * @var int $hp Represents the current health points (HP) of the player.
     */
    private int $hp;

    /**
     * Player constructor.
     *
     * @param string $type The type or category of the player.
     * @param int $startingHp The initial health points of the player.
     */
    protected function __construct(string $type, int $startingHp)
    {
        $this->type = $type;
        $this->hp = $startingHp;
    }

    /**
     * Gets the type of the player.
     *
     * @return string The type of the player.
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * Gets the current health points of the player.
     *
     * @return int The current health points of the player.
     */
    public function getHP(): int
    {
        return $this->hp;
    }

    /**
     * Performs an attack on the specified player, deducting damage from the attacked player's health points.
     *
     * @param Player $attackedPlayer The player being attacked.
     *
     * @return int The amount of damage inflicted during the attack.
     */
    public function attack(Player $attackedPlayer): int
    {
        $damage = $attackedPlayer->getDamage($this);
        $attackedPlayer->hp -= $damage;
        if ($attackedPlayer->hp < 0) {
            $attackedPlayer->hp = 0;
        }
        return $damage;
    }

    /**
     * Sets the player's health points to 0, effectively killing the player.
     */
    public function kill()
    {
        $this->hp = 0;
    }

    /**
     * Abstract method to be implemented by concrete subclasses. It calculates and returns the damage inflicted
     * by the specified attacker.
     *
     * @param Player $attacker The player performing the attack.
     *
     * @return int The amount of damage inflicted.
     */
    abstract public function getDamage(Player $attacker): int;
}
