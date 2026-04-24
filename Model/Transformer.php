<?php

namespace Vendor\M2Migration\Model;

use Vendor\M2Migration\Model\Reader\MapReader;

class Transformer
{
    private $reader;
    private $rowIdResolver;

    public function __construct(
        MapReader $reader,
        RowIdResolver $rowIdResolver
    ) {
        $this->reader = $reader;
        $this->rowIdResolver = $rowIdResolver;
    }

    /**
     * Main transformation pipeline
     */
    public function transform(string $table, array $row): ?array
    {
        // 1. Remove global ignored fields
        $row = $this->removeIgnoredFields($row);

        // 2. Handle row_id → entity_id (CRITICAL)
        if ($this->reader->isRowIdTable($table)) {
            $row = $this->handleRowId($table, $row);
        }

        return $row;
    }

    /**
     * Remove staging / ignored fields
     */
    private function removeIgnoredFields(array $row): array
    {
        unset($row['created_in']);
        unset($row['updated_in']);

        return $row;
    }

    /**
     * Handle row_id → entity_id mapping
     */
    private function handleRowId(string $table, array $row): array
    {
        if (!isset($row['row_id'])) {
            return $row;
        }

        $entityId = $this->rowIdResolver->resolve($table, $row['row_id']);

        // If mapping fails → skip row (important safety)
        if (!$entityId) {
            return null;
        }

        $row['entity_id'] = $entityId;

        unset($row['row_id']);

        return $row;
    }
}