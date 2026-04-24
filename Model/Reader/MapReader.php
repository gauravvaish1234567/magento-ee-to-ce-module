<?php

namespace Vendor\M2Migration\Model\Reader;

class MapReader
{
    private $xml;

    public function __construct()
    {
        $file = BP . '/app/code/Vendor/M2Migration/etc/m2map.xml';

        if (!file_exists($file)) {
            throw new \Exception("m2map.xml not found at: " . $file);
        }

        $this->xml = simplexml_load_file($file);
    }

    /**
     * =========================
     * IGNORE TABLES
     * =========================
     */
    public function getIgnoreTables(): array
    {
        $patterns = [];

        if (!isset($this->xml->ignore)) {
            return $patterns;
        }

        foreach ($this->xml->ignore->table as $table) {
            $patterns[] = (string)$table;
        }

        return $patterns;
    }

    /**
     * =========================
     * PRIORITY TABLES
     * =========================
     */
    public function getPriorityTables(): array
    {
        $tables = [];

        if (!isset($this->xml->priority)) {
            return $tables;
        }

        foreach ($this->xml->priority->table as $table) {
            $tables[] = (string)$table;
        }

        return $tables;
    }

    /**
     * =========================
     * CHECK ROW_ID TABLE
     * =========================
     */
    public function isRowIdTable(string $table): bool
    {
        if (!isset($this->xml->entity)) {
            return false;
        }

        foreach ($this->xml->entity as $entity) {

            $name = (string)$entity['name'];

            // match wildcard like catalog_product_entity_*
            $pattern = str_replace('%', '*', $name);

            if (fnmatch($pattern, $table)) {

                if (isset($entity->transform->use_row_id)) {
                    return (string)$entity->transform->use_row_id === 'true';
                }
            }
        }

        return false;
    }

    /**
     * =========================
     * GLOBAL SETTINGS
     * =========================
     */
    public function includeAllTables(): bool
    {
        return isset($this->xml->global->include_all_tables)
            && (string)$this->xml->global->include_all_tables === 'true';
    }

    public function autoMapFields(): bool
    {
        return isset($this->xml->global->auto_map_fields)
            && (string)$this->xml->global->auto_map_fields === 'true';
    }
}