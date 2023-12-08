<?php

namespace App;

/**
 * Class PlayerType
 *
 * The `PlayerType` class is a utility class that defines constants representing different types of players in the game.
 * It is used to categorize players such as humans, queen bees, worker bees, and drone bees.
 *
 * 
 */
abstract class PlayerType
{
    /**
     * Constant representing the human player type.
     */
    public const HUMAN = 'human';

    /**
     * Constant representing the queen bee player type.
     */
    public const QUEEN_BEE = 'queen_bee';

    /**
     * Constant representing the worker bee player type.
     */
    public const WORKER_BEE = 'worker_bee';

    /**
     * Constant representing the drone bee player type.
     */
    public const DRONE_BEE = 'drone_bee';
}