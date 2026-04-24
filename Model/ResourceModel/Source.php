<?php

namespace Vendor\M2Migration\Model\ResourceModel;

use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

class Source
{
    /**
     * @var AdapterInterface
     */
    protected $connection;

    public function __construct(ResourceConnection $resource)
    {
        // source DB connection (Commerce)
        $this->connection = $resource->getConnection('source_db');
    }

    /**
     * Get all tables from source DB
     */
    public function getTables(): array
    {
        return $this->connection->fetchCol('SHOW TABLES');
    }

    /**
     * Fetch full table (use only for small tables)
     */
    public function fetch(string $table): array
    {
        $tableName = $this->connection->getTableName($table);

        $select = $this->connection->select()
            ->from($tableName);

        return $this->connection->fetchAll($select);
    }

    /**
     * Fetch batch data (MAIN METHOD)
     */
   public function fetchBatch(string $table, int $limit, int $offset): array
{
    $tableName = $this->connection->getTableName($table);

    $orderField = $this->getOrderField($tableName);

    $select = $this->connection->select()
        ->from($tableName)
        ->order($orderField . ' ASC')
        ->limit($limit, $offset);

    return $this->connection->fetchAll($select);
}

    /**
     * Run custom query (used in RowIdResolver)
     */
    public function fetchAll(string $query): array
    {
        return $this->connection->fetchAll($query);
    }

    /**
     * Get raw connection (if needed)
     */
    public function getConnection(): AdapterInterface
    {
        return $this->connection;
    }
    private function getOrderField(string $table): string
{
    $columns = $this->connection->describeTable($table);

    // Priority order (VERY IMPORTANT)
    if (isset($columns['row_id'])) {
        return 'row_id';
    }

    if (isset($columns['entity_id'])) {
        return 'entity_id';
    }

    if (isset($columns['value_id'])) {
        return 'value_id';
    }

    if (isset($columns['config_id'])) {
        return 'config_id';
    }

    if (isset($columns['item_id'])) {
        return 'item_id';
    }

    if (isset($columns['id'])) {
        return 'id';
    }

    // fallback (last option)
    return array_key_first($columns);
}
}