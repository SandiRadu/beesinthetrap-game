<?php
namespace App\Entity\Tests;

use App\Entity\Bee;
use App\Game;
use App\PlayerType;
use PHPUnit\Framework\TestCase;

class GameTest extends TestCase
{
    function testStartGame()
    {
        $game = new Game();
        $game->startGame();

        $this->assertNotNull($game->human);
        $this->assertEquals(100, $game->human->getHP());

        $this->assertNotNull($game->queenBee);
        $this->assertEquals(100, $game->queenBee->getHP());
        $this->assertEquals(10, $game->queenBee->getDamage($game->human));

        $this->assertNotNull($game->workerBees);
        $this->assertEquals(5, count($game->workerBees));
        foreach ($game->workerBees as $worker) {
            $this->assertEquals(75, $worker->getHP());
            $this->assertEquals(25, $worker->getDamage($game->human));
        }

        $this->assertNotNull($game->droneBees);
        $this->assertEquals(25, count($game->droneBees));
        foreach ($game->droneBees as $drone) {
            $this->assertEquals(60, $drone->getHP());
            $this->assertEquals(30, $drone->getDamage($game->human));
        }
    }

    function testIsPlayingTrue()
    {
        $game = new Game();
        $game->startGame();

        $this->assertTrue($game->isPlaying());
    }

    function testIsPlayingFalseWhenHumanIsDead()
    {
        $game = new Game();
        $game->startGame();

        $game->human->kill();

        $this->assertFalse($game->isPlaying());
    }

    function testIsPLayingFalseWhenAllBeesAreDead()
    {
        $game = new Game();
        $game->startGame();

        foreach ($game->getAliveBees() as $bee) {
            $bee->kill();
        }

        $this->assertFalse($game->isPlaying());
    }

    function testKillAllBeesWhenQueenBeeIsDead()
    {
        $game = $this->getMockBuilder(Game::class)
            ->setMethods(['pickRandomBee'])
            ->getMock();
        $game->startGame();
        $game->method('pickRandomBee')
            ->willReturn($game->queenBee);

        for ($i = 0; $i < 10; $i++) {
            $game->hit();
        }

        $this->assertFalse($game->isPlaying());
        $this->assertEquals(0, $game->queenBee->getHP());
    }

    function testGameOverWhenHumanIsDead()
    {
        $game = $this->getMockBuilder(Game::class)
            ->setMethods(['pickRandomBee'])
            ->getMock();
        $game->startGame();

        $returnedBees = [];

        for ($i = 0; $i < 10; $i++) {
            array_push($returnedBees, null, $game->queenBee);
        }

        $game->method('pickRandomBee')
            ->willReturn(...$returnedBees);

        for ($i = 0; $i < 10; $i++) {
            $game->hit();
        }
        $this->assertFalse($game->isPlaying());
        $this->assertEquals(0, $game->human->getHP());
    }

    function testGetAliveBeesIsArray()
    {
        $game = new Game();
        $game->startGame();

        $this->assertIsArray($game->getAliveBees());
        foreach ($game->getAliveBees() as $bee) {
            $this->assertGreaterThan(0, $bee->getHP());
        }

    }

    function testGetAliveBeesWithQueenBee()
    {
        $game = new Game();
        $game->startGame();

        $this->assertContains($game->queenBee, $game->getAliveBees());
    }

    function testGetAliveBeesWithoutQueenBee()
    {
        $game = new Game();
        $game->startGame();

        $game->queenBee->kill();
        $this->assertNotContains(null, $game->getAliveBees());
    }
}