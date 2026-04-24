<?php

namespace Vendor\M2Migration\Model;

use Vendor\M2Migration\Model\ResourceModel\Source;

class RowIdResolver
{
    private $source;

    /**
     * Cache for row_id → entity_id mappings
     * [
     *   'catalog_product_entity' => [row_id => entity_id]
     * ]
     */
    private $map = [];

    public function __construct(Source $source)
    {
        $this->source = $source;
    }

    /**
     * Resolve row_id → entity_id
     */
    public function resolve(string $table, int $rowId): ?int
    {
        $mainTable = $this->getMainTable($table);

        // Load mapping once per table
        if (!isset($this->map[$mainTable])) {
            $this->loadMap($mainTable);
        }

        return $this->map[$mainTable][$rowId] ?? null;
    }

    /**
     * Load mapping from DB
     */
    private function loadMap(string $mainTable)
    {
        echo "Loading row_id map for: $mainTable\n";

        $rows = $this->source->fetchAll(
            "SELECT row_id, entity_id FROM $mainTable"
        );

        foreach ($rows as $row) {
            $this->map[$mainTable][$row['row_id']] = $row['entity_id'];
        }

        echo "Loaded " . count($rows) . " mappings\n";
    }

    /**
     * Detect correct base entity table
     */
    private function getMainTable(string $table): string
    {
        // PRODUCT TABLES
        if (strpos($table, 'catalog_product') !== false) {
            return 'catalog_product_entity';
        }

        // CATEGORY TABLES
        if (strpos($table, 'catalog_category') !== false) {
            return 'catalog_category_entity';
        }

        // RULE TABLES (EE specific)
        if (strpos($table, 'catalogrule') !== false) {
            return 'catalogrule_product';
        }

        // fallback (rare case)
        return $table;
    }
}