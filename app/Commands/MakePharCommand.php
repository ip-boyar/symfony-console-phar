<?php

namespace App\Commands;

use Phar;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class MakePharCommand extends Command
{
    protected static $defaultName = 'dev:make-phar';

    protected function configure()
    {
        $this
            ->setDescription('Creating a phar archive')
            ->setHelp('Create a Phar archive with all commands and tools');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $rootDir = dirname($_SERVER['SCRIPT_FILENAME']);
        
        $p = new Phar($rootDir . '/console.phar');
        $p->buildFromDirectory($rootDir, '/\.php$/');
        $p->setDefaultStub(basename($_SERVER['SCRIPT_FILENAME']), '/' . basename($_SERVER['SCRIPT_FILENAME']));

        $output->writeln('Phar archive created: console.phar');

        return 0;
    }

    /**
     * Hide the command if it is launched from a phar archive
     * @return bool
     */
    public function isEnabled()
    {
        return Phar::running() === '';
    }
}
