<?php

namespace Vendor\M2Migration\Console\Command;

use Vendor\M2Migration\Model\Migration;
use Vendor\M2Migration\Model\TableGroups;
class CategoryCommand extends AbstractMigrateCommand
{
    private $groups;

    public function __construct(Migration $migration, TableGroups $groups)
    {
        $this->groups = $groups;
        parent::__construct($migration);
    }

    protected function configure()
    {
        $this->setName('m2:migrate:categories');
    }

    protected function getTables(): array
    {
        return $this->groups->category();
    }
}