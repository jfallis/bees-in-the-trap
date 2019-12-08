<?php

namespace JohnFallisTests\Component;

use Prophecy\Argument;
use JohnFallis\Model\Bee;
use PHPUnit\Framework\TestCase;
use JohnFallis\Component\Settings;
use JohnFallis\Model\HiveCollection;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\SerializerInterface;

class SettingsTest extends TestCase
{
    /**
     * @var SerializerInterface|ObjectProphecy
     */
    private $serialiser;

    private Settings $settings;

    public function testSettings()
    {
        $filePath = sprintf('%s/../config/settings.yml', __DIR__);
        $hiveCollection = $this->prophesize(HiveCollection::class);

        $this->serialiser
            ->deserialize(
                Argument::type('string'),
                Bee::class . '[]',
                'yaml'
            )
            ->shouldBeCalledTimes(1)
            ->willReturn($hiveCollection->reveal());

        $this->assertInstanceOf(
            Settings::class,
            $this->settings->import(sprintf('%s/../config/settings.yml', __DIR__))
        );

        $this->assertInstanceOf(
            HiveCollection::class,
            $this->settings->getHive()
        );
    }

    protected function setUp(): void
    {
        $this->serialiser = $this->prophesize(SerializerInterface::class);

        $this->settings = new Settings($this->serialiser->reveal());
    }
}
