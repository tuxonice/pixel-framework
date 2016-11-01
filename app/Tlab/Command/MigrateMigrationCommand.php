<?php
namespace Tlab\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Tlab\Libraries\Migration;


class MigrateMigrationCommand extends Command
{
    protected function configure()
    {
        $this  
        ->setName('migrations:migrate')

        // the short description shown while running "php bin/console list"
        ->setDescription('Run a migration.')

        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to run a migration")
    ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        
        // outputs multiple lines to the console (adding "\n" at the end of each line)
        $output->writeln('Running the migration');
        $obj = new Migration();
        $obj->migrateMigration();
    }
}