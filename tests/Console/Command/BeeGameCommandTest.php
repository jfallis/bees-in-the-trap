<?php

namespace JohnFallisTests\Console\Command;

use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use JohnFallis\Console\Command\BeeGameCommand;
use Symfony\Component\Console\Tester\CommandTester;

class BeeGameCommandTest extends TestCase
{
    public function testBasicValues()
    {
        $beeGameCommand = new BeeGameCommand();

        $this->assertEquals('bee:game', $beeGameCommand->getName());
        $this->assertEquals('Bees In The Trap by John Fallis', $beeGameCommand->getDescription());
    }

    public function testExitBeforeGameBegins()
    {
        $application = new Application('Test', '0.9.0');

        $beeGameCommand = new BeeGameCommand();

        $application->add($beeGameCommand);
        $command = $application->find('bee:game');

        $commandTester = new CommandTester($command);

        // Question - Joshua: Shall we play a game? [y/n]
        $commandTester->setInputs(['no']);

        $this->assertEquals(0, $commandTester->execute(['command' => $command->getName()]));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Joshua: Shall we play a game?', $output);
        $this->assertStringContainsString('Joshua: A strange game. The only winning move is not to play. How about a nice game of chess?', $output);
    }

    public function testContinueShallWePlayAGame()
    {
        $application = new Application('Test', '0.9.0');

        $beeGameCommand = new BeeGameCommand();

        $application->add($beeGameCommand);
        $command = $application->find('bee:game');

        $commandTester = new CommandTester($command);

        // Question - Joshua: Shall we play a game? [y/n]
        // Question - Type [hit] to take your turn
        $commandTester->setInputs(['', ''] + array_fill(0, 100, 'hit'));

        $this->assertEquals(0, $commandTester->execute(['command' => $command->getName()]));

        // the output of the command in the console
        $output = $commandTester->getDisplay();

        $this->assertStringContainsString('Joshua: Shall we play a game?', $output);

        $this->assertStringContainsString('Bee Hive', $output);
        $this->assertRegexp('/\* LifespanPoints: \d+/', $output);
        $this->assertRegexp('/\* HitPoints: \d+/', $output);
        $this->assertRegexp('/\* TotalCount: \d+/', $output);
        $this->assertRegexp('/\* Nuke: (true|false)+/', $output);

        $this->assertRegexp('/\[ERROR\] You can never leave!/', $output);

        $this->assertRegexp('/\[OK\] Direct Hit\. You took \d+ hit points from a Drone bee/', $output);
        $this->assertRegexp('/\[OK\] It took you \[\d+\] turns to win!/', $output);
    }
}
