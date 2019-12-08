<?php

namespace JohnFallisTests\Component;

use JohnFallis\Component\Game;
use PHPUnit\Framework\TestCase;
use JohnFallis\Component\Message;
use JohnFallis\Model\HiveCollection;

class GameTest extends TestCase
{
    public function testSettings()
    {
        $game = new Game();

        $this->assertInstanceOf(
            Game::class,
            $game
        );
        $this->assertInstanceOf(
            HiveCollection::class,
            $game->getHive()
        );
    }

    public function testQueenScoring()
    {
        $type = 'queen';
        $game = new Game();

        $this->assertEquals(100, $game->getTypeScore($type));

        $game->recordHit($type);
        $this->assertEquals(92, $game->getTypeScore($type));
        // $this->assertFalse($game->isGameOver());

        for ($i = 0; $i < 12; $i++) {
            $game->recordHit($type);
        }

        $this->assertEquals(0, $game->getTypeScore($type));
        $this->assertTrue($game->isTypeDead($type));
        // $this->assertTrue($game->isGameOver());
    }

    public function testWorkerScoring()
    {
        $type = 'worker';
        $game = new Game();

        $this->assertEquals(375, $game->getTypeScore($type));
        $this->assertFalse($game->isGameOver());

        $game->recordHit($type);
        $this->assertEquals(365, $game->getTypeScore($type));

        for ($i = 0; $i < 46; $i++) {
            $game->recordHit($type);
        }

        $this->assertEquals(0, $game->getTypeScore($type));
        $this->assertTrue($game->isTypeDead($type));
        $this->assertFalse($game->isGameOver());

        for ($i = 0; $i < 13; $i++) {
            $game->recordHit('queen');
        }

        $this->assertTrue($game->isGameOver());
    }

    public function testDroneScoring()
    {
        $type = 'drone';
        $game = new Game();

        $this->assertEquals(400, $game->getTypeScore($type));
        $this->assertFalse($game->isGameOver());

        $game->recordHit($type);
        $this->assertEquals(388, $game->getTypeScore($type));

        for ($i = 0; $i < 33; $i++) {
            $game->recordHit($type);
        }

        $this->assertEquals(0, $game->getTypeScore($type));
        $this->assertTrue($game->isTypeDead($type));
        $this->assertFalse($game->isGameOver());

        for ($i = 0; $i < 13; $i++) {
            $game->recordHit('queen');
        }

        $this->assertTrue($game->isGameOver());
    }

    public function testRandomHits()
    {
        $game = new Game();
        $totalHits = 0;

        while (!$game->isGameOver()) {
            $hit = $game->randomHit();
            $totalHits++;

            $this->assertInstanceOf(Message::class, $hit);
            $this->assertIsString((string) $game->randomHit());
        }

        $this->assertGreaterThan(5, $totalHits);
    }
}
