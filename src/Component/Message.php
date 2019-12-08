<?php

namespace JohnFallis\Component;

class Message
{
    private string $beeName;

    private int $hitPoints;

    public function __construct(string $beeName, int $hitPoints)
    {
        $this->beeName = ucfirst(strtolower($beeName));
        $this->hitPoints = $hitPoints;
    }

    public function getMessage(): string
    {
        return 'Direct Hit. You took %d hit points from a %s bee';
    }

    public function __toString(): string
    {
        return sprintf($this->getMessage(), $this->hitPoints, $this->beeName);
    }
}
