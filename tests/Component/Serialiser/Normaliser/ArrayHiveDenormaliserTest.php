<?php

namespace JohnFallisTests\Component\Serialiser\Normaliser;

use Prophecy\Argument;
use JohnFallis\Model\ArrayHive;
use PHPUnit\Framework\TestCase;
use Prophecy\Prophecy\ObjectProphecy;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Serializer\SerializerAwareInterface;
use Symfony\Component\Serializer\Exception\BadMethodCallException;
use Symfony\Component\Serializer\Exception\InvalidArgumentException;
use JohnFallis\Component\Serialiser\Normaliser\ArrayHiveDenormaliser;
use Symfony\Component\Serializer\Normalizer\CacheableSupportsMethodInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareDenormalizerInterface;

class ArrayHiveDenormaliserTest extends TestCase
{
    private ArrayHiveDenormaliser $denormalizer;

    public function testArrayHiveDenormaliser()
    {
        $this->assertInstanceOf(ContextAwareDenormalizerInterface::class, $this->denormalizer);
        $this->assertInstanceOf(SerializerAwareInterface::class, $this->denormalizer);
        $this->assertInstanceOf(CacheableSupportsMethodInterface::class, $this->denormalizer);
    }

    public function testSupportsNoArray()
    {
        $this->assertFalse(
            $this->denormalizer->supportsDenormalization(
                ['foo' => 'one', 'bar' => 'two'],
                ArrayDummy::class
            )
        );
    }

    public function testHasCacheableSupportsMethod()
    {
        $this->assertFalse($this->denormalizer->hasCacheableSupportsMethod());
    }

    public function testDenormalizeWithMissingSerialiser()
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('Please set a serializer before calling denormalize()!');

        $denormalizer = new ArrayHiveDenormaliser(new ArrayHive());
        $denormalizer->denormalize(null, 'string');
    }

    public function testDenormalizeWithInvalidData()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Data expected to be an array.+/');

        $this->denormalizer->denormalize(null, 'string');
    }

    public function testDenormalizeWithInvalidType()
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Unsupported class:.+/');

        $this->denormalizer->denormalize([], 'string');
    }

    public function testInvalidSerialiser(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('Expected a serializer that also implements DenormalizerInterface.');

        $this->serialiser = $this->prophesize(SerializerInterface::class);

        $this->denormalizer = new ArrayHiveDenormaliser(new ArrayHive());
        $this->denormalizer->setSerializer($this->serialiser->reveal());
    }

    /**
     * @var Serializer|ObjectProphecy
     */
    private $serialiser;

    public function testDenormalize()
    {
        $this->serialiser
            ->denormalize(['foo' => 'one', 'bar' => 'two'], ArrayDummy::class, null, [])
            ->shouldBeCalledTimes(1)
            ->willReturn(new ArrayDummy('one', 'two'));

        $this->serialiser
            ->denormalize(['foo' => 'three', 'bar' => 'four'], ArrayDummy::class, null, [])
            ->shouldBeCalledTimes(1)
            ->willReturn(new ArrayDummy('three', 'four'));

        $result = $this->denormalizer->denormalize(
            [
                'one' => ['foo' => 'one', 'bar' => 'two'],
                'two' => ['foo' => 'three', 'bar' => 'four'],
            ],
            __NAMESPACE__ . '\ArrayDummy[]'
        );

        $expected = new ArrayHive();
        $expected->set('one', new ArrayDummy('one', 'two'));
        $expected->set('two', new ArrayDummy('three', 'four'));
        $this->assertEquals(
            $expected,
            $result
        );
    }

    public function testSupportsValidArray()
    {
        $this->serialiser
            ->supportsDenormalization(
                [
                    ['foo' => 'one', 'bar' => 'two'],
                    ['foo' => 'three', 'bar' => 'four'],
                ],
                ArrayDummy::class,
                null,
                []
            )
            ->shouldBeCalledTimes(1)
            ->willReturn(true);

        $this->assertTrue(
            $this->denormalizer->supportsDenormalization(
                [
                    ['foo' => 'one', 'bar' => 'two'],
                    ['foo' => 'three', 'bar' => 'four'],
                ],
                ArrayDummy::class . '[]'
            )
        );
    }

    public function testSupportsInvalidArray()
    {
        $this->serialiser
            ->supportsDenormalization(
                Argument::any(),
                Argument::type('string'),
                null,
                []
            )
            ->shouldBeCalled()
            ->willReturn(false);

        $this->assertFalse(
            $this->denormalizer->supportsDenormalization(
                [
                    ['foo' => 'one', 'bar' => 'two'],
                    ['foo' => 'three', 'bar' => 'four'],
                ],
                __NAMESPACE__ . '\InvalidClass[]'
            )
        );
    }

    protected function setUp(): void
    {
        $this->serialiser = $this->prophesize(Serializer::class);

        $this->denormalizer = new ArrayHiveDenormaliser(new ArrayHive());
        $this->denormalizer->setSerializer($this->serialiser->reveal());
    }
}

class ArrayDummy
{
    public $foo;
    public $bar;

    public function __construct($foo, $bar)
    {
        $this->foo = $foo;
        $this->bar = $bar;
    }
}
