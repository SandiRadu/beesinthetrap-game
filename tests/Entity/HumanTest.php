<?php
namespace App\Entity\Tests;

use App\Entity\Bee;
use App\Entity\Human;
use App\PlayerType;
use PHPUnit\Framework\TestCase;

class HumanTest extends TestCase
{
    function testDamageQueenBee()
    {
        $human = new Human(0);
        $queenBee = new Bee(PlayerType::QUEEN_BEE, 100, 0);
        $this->assertEquals(10, $human->getDamage($queenBee));
    }

    function testDamageDroneBee()
    {
        $human = new Human(100);
        $droneBee = new Bee(PlayerType::DRONE_BEE, 75, 0);

        $this->assertEquals(1, $human->getDamage($droneBee));
    }

    function testDamageWorkerBee()
    {
        $human = new Human(100);
        $workerBee = new Bee(PlayerType::WORKER_BEE, 75, 0);

        $this->assertEquals(5, $human->getDamage($workerBee));
    }

    function testExceptionDamageHuman()
    {
        $humanAttacker = new Human(100);
        $humanAttacked = new Human(100);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Human cannot be hit by ');

        $humanAttacker->getDamage($humanAttacked);
    }

}