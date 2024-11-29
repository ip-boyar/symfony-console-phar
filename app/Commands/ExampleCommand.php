<?php

namespace App\Commands;

use Phar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ExampleCommand extends Command
{
    protected static $defaultName = 'demo:example';

    protected function configure()
    {
        $this
            ->setDescription('Example command')
            ->setHelp('Example command');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello world!');

        return self::SUCCESS;
    }
}
