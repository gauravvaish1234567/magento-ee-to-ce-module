<?php

namespace Vendor\M2Migration\Console\Command;

use Vendor\M2Migration\Model\TableGroups;

class BaseCommand extends AbstractMigrateCommand
{
    private $groups;

    public function __construct(
        \Vendor\M2Migration\Model\Migration $migration,
        TableGroups $groups
    ) {
        $this->groups = $groups;
        parent::__construct($migration);
    }

    protected function configure()
    {
        $this->setName('m2:migrate:base');
    }

    protected function getTables(): array
    {
        return $this->groups->base();
    }
}