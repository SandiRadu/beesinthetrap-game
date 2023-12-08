<?php

namespace App;

use App\Player;

/**
 * Class Hit
 *
 * The `Hit` class represents an instance of an attack in the game. It encapsulates information about the attacking player,
 * the attacked player, and the damage inflicted during the attack.
 *
 * 
 */
class Hit
{
    /**
     * @var Player|null $attackerPlayer The player initiating the attack. Can be null if the attacker is not a player.
     */
    public Player|null $attackerPlayer;

    /**
     * @var Player|null $attackedPlayer The player being attacked. Can be null if the attacked entity is not a player.
     */
    public Player|null $attackedPlayer;

    /**
     * @var int $damage The amount of damage inflicted during the attack.
     */
    public int $damage;

    /**
     * Hit constructor.
     *
     * @param Player|null $attackerPlayer The player initiating the attack. Can be null if the attacker is not a player.
     * @param Player|null $attackedPlayer The player being attacked. Can be null if the attacked entity is not a player.
     * @param int $damage The amount of damage inflicted during the attack.
     */
    public function __construct(Player|null $attackerPlayer, Player|null $attackedPlayer, int $damage)
    {
        $this->attackedPlayer = $attackedPlayer;
        $this->attackerPlayer = $attackerPlayer;
        $this->damage = $damage;
    }
}
