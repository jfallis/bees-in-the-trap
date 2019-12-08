<?php

require __DIR__ . '/vendor/autoload.php';

use Symfony\Component\Console\Application;
use JohnFallis\Console\Command\BeeGameCommand;

$beeGameCommand = new BeeGameCommand();

$app = new Application('Bees In The Trap', '1.0.0');

$app->add($beeGameCommand);
$app->setDefaultCommand($beeGameCommand->getName());

$app->run();
