<?php

namespace Vendor\M2Migration\Model;

use Vendor\M2Migration\Model\ResourceModel\Source;
use Vendor\M2Migration\Model\ResourceModel\Destination;
use Vendor\M2Migration\Model\Reader\MapReader;
use Vendor\M2Migration\Model\Transformer;

class Migration
{
    private $source;
    private $destination;
    private $reader;
    private $transformer;

    private $batchSize = 200;

    public function __construct(
        Source $source,
        Destination $destination,
        MapReader $reader,
        Transformer $transformer
    ) {
        $this->source = $source;
        $this->destination = $destination;
        $this->reader = $reader;
        $this->transformer = $transformer;
    }

    /**
     * Run full migration (all tables)
     */
    public function run()
    {
        $tables = $this->source->getTables();

        $tables = $this->filterTables($tables);

        $tables = $this->sortTables($tables);

        foreach ($tables as $table) {
            $this->migrateTable($table);
        }
    }

    /**
     * Run only specific tables (used by commands)
     */
    public function runByTables(array $tables)
    {
        foreach ($tables as $table) {
            $this->migrateTable($table);
        }
    }

    /**
     * Filter ignored tables
     */
    private function filterTables(array $tables): array
    {
        $ignorePatterns = $this->reader->getIgnoreTables();

        return array_filter($tables, function ($table) use ($ignorePatterns) {

            foreach ($ignorePatterns as $pattern) {
                $pattern = str_replace('%', '*', $pattern);

                if (fnmatch($pattern, $table)) {
                    return false;
                }
            }

            return true;
        });
    }

    /**
     * Apply priority sorting
     */
    private function sortTables(array $tables): array
    {
        $priorityTables = $this->reader->getPriorityTables();

        usort($tables, function ($a, $b) use ($priorityTables) {

            $posA = array_search($a, $priorityTables);
            $posB = array_search($b, $priorityTables);

            $posA = ($posA === false) ? 999 : $posA;
            $posB = ($posB === false) ? 999 : $posB;

            return $posA <=> $posB;
        });

        return $tables;
    }

    /**
     * Core table migration logic
     */
    private function migrateTable(string $table)
    {
        echo "=============================\n";
        echo "Migrating Table: $table\n";

        $offset = 0;

        do {
            $rows = $this->source->fetchBatch($table, $this->batchSize, $offset);

            if (empty($rows)) {
                break;
            }

            $processedRows = [];

            foreach ($rows as $row) {

                $row = $this->transformer->transform($table, $row);

                // Skip if transformation failed (rare but safe)
                if ($row === null) {
                    continue;
                }

                $processedRows[] = $row;
            }

            if (!empty($processedRows)) {
                $this->destination->insertBatch($table, $processedRows);
            }

            $offset += $this->batchSize;

            echo "Processed: $offset rows\n";

        } while (true);

        echo "Completed Table: $table\n";
    }
}