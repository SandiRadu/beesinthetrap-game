<?php
namespace App\Entity\Tests;

use App\Entity\Bee;
use App\Entity\Human;
use App\PlayerType;
use PHPUnit\Framework\TestCase;

class BeeTest extends TestCase
{
    function testBeeTypeInvalid()
    {
        $this->expectException(\InvalidArgumentException::class);
        new Bee(PlayerType::HUMAN, 0, 0);
    }

}