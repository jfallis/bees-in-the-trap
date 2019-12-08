<?php

namespace JohnFallisTests\Component;

use PHPUnit\Framework\TestCase;
use JohnFallis\Component\Message;

class MessageTest extends TestCase
{
    public function testMessageGetMessage()
    {
        $message = new Message('name', 123);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals('Direct Hit. You took %d hit points from a %s bee', $message->getMessage());
    }

    public function testMessageWithUnderscore()
    {
        $message = new Message('name', 123);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals('Direct Hit. You took 123 hit points from a Name bee', (string) $message);
    }

    public function testMessageWithCaps()
    {
        $message = new Message('NAME', 123);

        $this->assertInstanceOf(Message::class, $message);
        $this->assertEquals('Direct Hit. You took 123 hit points from a Name bee', (string) $message);
    }
}
