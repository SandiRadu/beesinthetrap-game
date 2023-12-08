<?php

namespace App;


use App\Entity\Bee;
use App\Entity\Human;
use App\Hit;

/**
 * Class Game
 *
 * The `Game` class represents the main game logic for a bee-fighting game. It manages human and bee players, tracks hits,
 * and determines game state.
 *
 * 
 */
class Game
{
    /**
     * @var Player $human The human player in the game.
     */
    public Player $human;

    /**
     * @var Player $queenBee The queen bee player in the game.
     */
    public Player $queenBee;

    /**
     * @var Player[] $workerBees An array of worker bee players in the game.
     */
    public array $workerBees;

    /**
     * @var Player[] $droneBees An array of drone bee players in the game.
     */
    public array $droneBees;

    /**
     * @var Hit[] $hits An array of hit instances, representing attacks by the human player.
     */
    public array $hits;

    /**
     * @var Hit[] $stings An array of hit instances, representing attacks by bee players.
     */
    public array $stings;

    /**
     * Initializes the game by creating human and bee players.
     */
    public function startGame(): void
    {
        $this->human = new Human(100);
        $this->queenBee = new Bee(PlayerType::QUEEN_BEE, 100, 10);

        $this->workerBees = [];
        foreach (range(1, 5) as $i) {
            $this->workerBees[] = new Bee(PlayerType::WORKER_BEE, 75, 25);
        }

        $this->droneBees = [];
        foreach (range(1, 25) as $i) {
            $this->droneBees[] = new Bee(PlayerType::DRONE_BEE, 60, 30);
        }

        $this->hits = [];
        $this->stings = [];
    }

    /**
     * Checks if the game is still active by examining the health points of the human player and the number of alive bees.
     *
     * @return bool Returns true if the game is still active, false otherwise.
     */
    public function isPlaying(): bool
    {
        return $this->human->getHP() > 0 && count($this->getAliveBees()) > 0;
    }

    /**
     * Initiates an attack by the human player on a randomly selected bee. Also triggers a counter-attack by a randomly selected bee.
     *
     * @return Hit[] An array of Hit instances representing the attacks.
     */
    public function hit(): array
    {
        $bee = $this->pickRandomBee();
        $gameOver = false;
        $firstDamage = 0;

        if (!is_null($bee)) {
            $firstDamage = $this->human->attack($bee);

            if ($bee->getType() == PlayerType::QUEEN_BEE && $bee->getHP() == 0) {
                foreach ($this->getAliveBees() as $aliveBee) {
                    $aliveBee->kill();
                    $gameOver = true;
                }
            }
        }

        $firstHit = new Hit($this->human, $bee, $firstDamage);
        $this->hits[] = $firstHit;

        if (!$gameOver) {
            $attackerBee = $this->pickRandomBee();
            $secondDamage = 0;

            if (!is_null($attackerBee)) {
                $secondDamage = $attackerBee->attack($this->human);
            }

            $secondHit = new Hit($attackerBee, $this->human, $secondDamage);
            $this->stings[] = $secondHit;
            return [$firstHit, $secondHit];
        }

        return [$firstHit];
    }

    /**
     * Retrieves an array of alive bees in the game, including the queen bee, worker bees, and drone bees.
     *
     * @return Player[] An array of alive bee players.
     */
    public function getAliveBees(): array
    {
        $aliveBees = array();

        if ($this->queenBee->getHP() > 0) {
            $aliveBees[] = $this->queenBee;
        }

        array_push($aliveBees,
            ...$this->filterAlivePlayers($this->droneBees),
            ...$this->filterAlivePlayers($this->workerBees)
        );

        return $aliveBees;
    }

    /**
     * Selects a bee randomly from the alive bees array, with equal chances for each bee type.
     *
     * @return Player|null The selected bee or null if no bee is alive.
     */
    protected function pickRandomBee(): Player|null
    {
        $aliveBees = $this->getAliveBees();
        $typeCounts = array_count_values(array_map(function ($obj) {
            return $obj->getType();
        }, $aliveBees));

        $selectedType = array_rand([null, ...$typeCounts]);
        $selectedElement = null;

        foreach ($aliveBees as $bee) {
            if ($bee->getType() === $selectedType) {
                $selectedElement = $bee;
            }
        }

        return $selectedElement;
    }

    /**
     * Filters out players with zero health points from the given array of players.
     *
     * @param Player[] $players The array of players to filter.
     *
     * @return Player[] An array of alive players.
     */
    private function filterAlivePlayers(array $players): array
    {
        return array_filter($players, function (Player $player) {
            return $player->getHP() > 0;
        });
    }
}
