<?php

namespace App;

/**
 * The Player class is an abstract class representing a generic player in the game. It defines common properties and methods 
 * that can be extended and implemented by concrete player classes.
 */
abstract class Player
{
    private string $type;
    private int $hp;

    /**
     * @param string $type Represents the type of the player.
     * @param int $startingHp Represents the current health points (HP) of the player.
     */
    protected function __construct(string $type, int $startingHp)
    {
        $this->type = $type;
        $this->hp = $startingHp;
    }

    function getType(): string
    {
        return $this->type;
    }

    function getHP(): int
    {
        return $this->hp;
    }

    function attack(Player $attackedPlayer): int
    {
        $damage = $attackedPlayer->getDamage($this);
        $attackedPlayer->hp -= $damage;
        if ($attackedPlayer->hp < 0) {
            $attackedPlayer->hp = 0;
        }
        return $damage;
    }

    function kill()
    {
        $this->hp = 0;
    }

    abstract function getDamage(Player $attacker): int;
}