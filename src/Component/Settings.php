<?php

namespace JohnFallis\Component;

use JohnFallis\Model\Bee;
use JohnFallis\Model\HiveCollection;
use Symfony\Component\Serializer\SerializerInterface;

class Settings
{
    private SerializerInterface $serialiser;

    private ?HiveCollection $hive;

    public function __construct(SerializerInterface $serialiser)
    {
        $this->serialiser = $serialiser;
    }

    public function import(string $filepath): self
    {
        $this->hive = $this->serialiser->deserialize(file_get_contents($filepath), Bee::class . '[]', 'yaml');

        return $this;
    }

    public function getHive(): ?HiveCollection
    {
        return $this->hive;
    }
}
