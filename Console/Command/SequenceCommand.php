<?php

namespace Vendor\M2Migration\Console\Command;
use Vendor\M2Migration\Model\Migration;

class SequenceCommand extends AbstractMigrateCommand
{
    private $groups;

    public function __construct(Migration $migration, \Vendor\M2Migration\Model\TableGroups $groups)
    {
        $this->groups = $groups;
        parent::__construct($migration);
    }

    protected function configure()
    {
        $this->setName('m2:migrate:sequences');
    }

    protected function getTables(): array
    {
        return $this->groups->sequence();
    }
}