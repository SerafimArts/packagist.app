<?php

use App\Kernel;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\ConsoleOutput;

require __DIR__ . '/bootstrap.php';

// In case of PhpStorm env check.
if (\in_array('--version', (array) ($_SERVER['argv'] ?? []), true)) {
    return;
}

$commands = [
    [
        'command' => 'cache:clear',
        '--no-warmup' => true,
        '-n' => true,
    ],
    [
        'command' => 'doctrine:database:drop',
        '--connection' => 'default',
        '--force' => true,
        '--if-exists' => true,
    ],
    [
        'command' => 'doctrine:database:create',
        '--connection' => 'default',
    ],
    [
        'command' => 'doctrine:migrations:migrate',
        '--no-interaction' => true,
        '--quiet' => true,
    ],
];


$output = new ConsoleOutput();

//
// If this environment setting is specified, all bootstrap commands
// that are run will be ignored:
//
//  $ APP_NO_BOOTSTRAP=1 composer phpunit:integration
//
if (($_ENV['APP_NO_BOOTSTRAP'] ?? false) || ($_SERVER['APP_NO_BOOTSTRAP'] ?? false)) {
    $commands = [];
    $output->writeln(<<<'MESSAGE'

    <info>--------------------------------------------------</info>
     env <comment>APP_NO_BOOTSTRAP</comment> = 1
    <info> Rollback and applying migrations will be ignored </info>
    <info>--------------------------------------------------</info>

    MESSAGE);
}

foreach ($commands as $i => $command) {
    $name = $command['command'] ?? '<unknown#' . $i . '>';

    $kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);

    $app = new Application($kernel);
    $app->setAutoExit(false);

    $output->writeln('> php bin/console <info>' . $name . '</info>');

    $code = $app->run(new ArrayInput($command), $output);

    if ($code !== 0) {
        $message = \sprintf('An error occurred while executing "%s" command', $command['command']);
        $output->writeln('<error>' . $message . '</error>');

        exit($code);
    }

    $kernel->shutdown();
}
