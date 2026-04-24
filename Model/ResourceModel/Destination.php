<?php

namespace Vendor\M2Migration\Model\ResourceModel;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Adapter\AdapterInterface;

class Destination
{
    protected $connection;
    protected $logFile;

    public function __construct(ResourceConnection $resource)
    {
        $this->connection = $resource->getConnection('target_db');
        $this->logFile = BP . '/var/log/migration_errors.log';
    }

    public function insertBatch(string $table, array $records): void
    {
        if (empty($records)) {
            return;
        }
        $tableName = $this->connection->getTableName($table);
        try {
            $this->connection->insertMultiple($tableName, $records);
        } catch (\Exception $e) {
            $this->logError($tableName, 'BATCH_ERROR', $e->getMessage());
            foreach ($records as $record) {
                try {
                    $this->connection->insert($tableName, $record);
                } catch (\Exception $inner) {
                    $this->logError($tableName, json_encode($record), $inner->getMessage());
                }
            }
        }
    }

    private function logError(string $table, string $data, string $message): void
    {
        $log = sprintf(
            "[%s] TABLE: %s\nDATA: %s\nERROR: %s\n\n",
            date('Y-m-d H:i:s'),
            $table,
            $data,
            $message
        );
        file_put_contents($this->logFile, $log, FILE_APPEND);
    }

    public function truncate(string $table): void
    {
        $this->connection->truncateTable(
            $this->connection->getTableName($table)
        );
    }

    public function disableForeignKeys(): void
    {
        $this->connection->query('SET FOREIGN_KEY_CHECKS=0');
    }

    public function enableForeignKeys(): void
    {
        $this->connection->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function getConnection(): AdapterInterface
    {
        return $this->connection;
    }
}