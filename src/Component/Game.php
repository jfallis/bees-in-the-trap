<?php

namespace JohnFallis\Component;

use JohnFallis\Model\Bee;
use JohnFallis\Model\ArrayHive;
use JohnFallis\Model\HiveCollection;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\YamlEncoder;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;
use JohnFallis\Component\Serialiser\Normaliser\ArrayHiveDenormaliser;

class Game
{
    private Settings $settings;

    private int $userHits = 0;

    public function __construct()
    {
        $this->settings = new Settings($this->getSerialiser());

        $this->settings
            ->import(sprintf('%s/../config/settings.yml', __DIR__));
    }

    public function getTypeScore(string $type): float
    {
        /** @var Bee */
        $bee = $this->settings->getHive()->get($type);

        $totalScore = $bee->getLifespanPoints() * $bee->getTotalCount();
        $deductHitScore = $bee->getHits() * $bee->getHitPoints();
        $remainginScore = $totalScore - $deductHitScore;

        return $remainginScore >= 0 ? $remainginScore : 0;
    }

    public function isTypeDead(string $type): bool
    {
        return $this->getTypeScore($type) === 0.0;
    }

    public function recordHit(string $type)
    {
        /** @var Bee */
        $bee = $this->settings->getHive()->get($type);
        $bee->addHit();

        $this->settings->getHive()->set($type, $bee);
    }

    public function isGameOver(): bool
    {
        foreach ($this->settings->getHive() as $type => $bee) {
            if ($this->isTypeDead($type) && $bee->isNuke()) {
                return true;
            }
        }

        return false;
    }

    public function randomHit(): Message
    {
        $hive = $this->settings->getHive();
        $num = mt_rand(0, $hive->count() - 1);

        $bee = $hive->getKeys()[$num];
        $this->recordHit($bee);

        return new Message($bee, $hive->get($bee)->getHitPoints());
    }

    public function getHive(): HiveCollection
    {
        return $this->settings->getHive();
    }

    private function getSerialiser(): SerializerInterface
    {
        $encoders = [new YamlEncoder()];
        $normalizers = [new GetSetMethodNormalizer(), new ArrayHiveDenormaliser(new ArrayHive())];

        return new Serializer($normalizers, $encoders);
    }
}
