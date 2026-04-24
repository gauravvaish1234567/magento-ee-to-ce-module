<?php

namespace Vendor\M2Migration\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Vendor\M2Migration\Model\StepList;

class MigrateCommand extends Command
{
    protected $stepList;

    public function __construct(StepList $stepList)
    {
        $this->stepList = $stepList;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('m2:migrate')
             ->setDescription('Migrate data from Commerce to Open Source');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("<info>Starting Migration...</info>");

        $this->stepList->executeAll();

        $output->writeln("<info>Migration Completed</info>");
            return Command::SUCCESS; // ✅ IMPORTANT

    }
}