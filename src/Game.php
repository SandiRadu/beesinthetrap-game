<?php

namespace App;


use App\Entity\Bee;
use App\Entity\Human;
use App\Hit;

class Game
{
    public Player $human;

    public Player $queenBee;

    /**
     * @var Player[]
     */
    public array $workerBees;

    /**
     * @var Player[]
     */
    public array $droneBees;

    /**
     * @var array
     */
    public array $hits;

    public array $stings;

    function startGame(): void
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


    function isPlaying(): bool
    {
        return $this->human->getHP() > 0 && count($this->getAliveBees()) > 0;
    }

    /**
     * @return Hit[]
     */
    function hit(): array
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

        // Bee's turn

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
     * @return Player[]
     */
    function getAliveBees(): array
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
     * Selects randomly a bee, any bee having exactly the same chances to be picked. E.g. the queen bee, even if she's only one, she will
     * have the same chances as a drone bee (25) to be selected. It can also return null, with the same chances.
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
     * @param Player[] $players
     * @return Player[]
     */
    private function filterAlivePlayers(array $players): array
    {
        return array_filter($players, function (Player $player) {
            return $player->getHP() > 0;
        });
    }
}