<?php

namespace Vendor\M2Migration\Console\Command;
use Vendor\M2Migration\Model\Migration;
class CatalogRuleCommand extends AbstractMigrateCommand
{
    private $groups;

    public function __construct(Migration $migration, \Vendor\M2Migration\Model\TableGroups $groups)
    {
        $this->groups = $groups;
        parent::__construct($migration);
    }

    protected function configure()
    {
        $this->setName('m2:migrate:catalog-rules');
    }

    protected function getTables(): array
    {
        return ['catalogrule', 'catalogrule_product', 'catalogrule_product_price'];
    }
}