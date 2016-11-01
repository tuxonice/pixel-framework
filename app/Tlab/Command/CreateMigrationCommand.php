<?php
namespace Tlab\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Tlab\Libraries\Migration;


class CreateMigrationCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('migrations:create')

            // the short description shown while running "php bin/console list"
            ->setDescription('Create a migration.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command allows you to create a migration")
            ->addArgument('name', InputArgument::REQUIRED, 'The migration class name');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Creating migration...');
        $className = str_replace('_',' ',$input->getArgument('name'));
        $className = ucwords($className);
        $className = str_replace(' ','',$className);
        $filename = (new Migration())->createMigration($className);
        $output->writeln($filename. '... done!');
    }
}