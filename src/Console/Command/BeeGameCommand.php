<?php

namespace JohnFallis\Console\Command;

use JohnFallis\Component\Game;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BeeGameCommand extends Command
{
    protected static $defaultName = 'bee:game';

    protected function configure()
    {
        $this->setDescription('Bees In The Trap by John Fallis');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Bees In The Trap');

        if (!$io->confirm('Joshua: Shall we play a game?')) {
            $io->text('Joshua: A strange game. The only winning move is not to play. How about a nice game of chess?');

            return 0;
        }

        $game = new Game();

        foreach ($game->getHive() as $bee) {
            $io->section('Name: ' . $bee->getName());
            $io->text([
                'LifespanPoints: ' . $bee->getLifespanPoints(),
                'HitPoints: ' . $bee->getHitPoints(),
                'TotalCount: ' . $bee->getTotalCount(),
                'isNuke: ' . $bee->isNuke() ? 'true' : 'false',
                'getHits: ' . $bee->getHits(),
            ]);
        }

        $totalHits = 0;
        while (!$game->isGameOver()) {
            if ($io->ask('Type [hit] to take your turn') !== 'hit') {
                $io->error('You can never leave!');

                continue;
            }
            $io->success($game->randomHit());
            $totalHits++;
        }

        $io->section('Success! You Win!');
        $io->success([
            sprintf('It took you [%d] turns to win!', $totalHits),
        ]);

        return 0;
    }
}
