<?php

namespace Vendor\M2Migration\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vendor\M2Migration\Model\Migration;

abstract class AbstractMigrateCommand extends Command
{
    protected $migration;

    public function __construct(Migration $migration)
    {
        $this->migration = $migration;
        parent::__construct();
    }

    /**
     * Each child must return its table list
     */
    abstract protected function getTables(): array;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $tables = $this->getTables();

        $output->writeln("<info>Starting: " . $this->getName() . "</info>");

        foreach ($tables as $table) {
            $output->writeln("Migrating: " . $table);
        }

        $this->migration->runByTables($tables);

        $output->writeln("<info>Completed: " . $this->getName() . "</info>");

        return Command::SUCCESS;
    }
}