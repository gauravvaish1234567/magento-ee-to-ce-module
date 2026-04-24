<?php

namespace Vendor\M2Migration\Console\Command;
use Vendor\M2Migration\Model\Migration;
class CartCommand extends AbstractMigrateCommand
{
    private $groups;

    public function __construct(Migration $migration, \Vendor\M2Migration\Model\TableGroups $groups)
    {
        $this->groups = $groups;
        parent::__construct($migration);
    }

    protected function configure()
    {
        $this->setName('m2:migrate:cart');
    }

    protected function getTables(): array
    {
        return $this->groups->cart();
    }
}