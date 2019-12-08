<?php

namespace JohnFallis\Model;

class Bee
{
    private string $name = 'NOTSET';

    private int $lifespanPoints = 0;

    private int $hitPoints = 0;

    private int $totalCount = 0;

    private bool $nuke = false;

    private int $hits = 0;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName($name): void
    {
        $this->name = $name;
    }

    public function getLifespanPoints(): int
    {
        return $this->lifespanPoints;
    }

    public function setLifespanPoints($lifespanPoints): void
    {
        $this->lifespanPoints = $lifespanPoints;
    }

    public function getHitPoints(): int
    {
        return $this->hitPoints;
    }

    public function setHitPoints($hitPoints): void
    {
        $this->hitPoints = $hitPoints;
    }

    public function getTotalCount(): int
    {
        return $this->totalCount;
    }

    public function setTotalCount($totalCount): void
    {
        $this->totalCount = $totalCount;
    }

    public function setNuke(bool $nuke): void
    {
        $this->nuke = $nuke;
    }

    public function isNuke(): bool
    {
        return $this->nuke;
    }

    public function addHit()
    {
        $this->hits++;
    }

    public function getHits()
    {
        return $this->hits;
    }
}
