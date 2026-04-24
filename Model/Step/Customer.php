<?php

namespace Vendor\M2Migration\Model\Step;

use Psr\Log\LoggerInterface;

class Customer implements StepInterface
{
    protected $source;
    protected $destination;
    protected $mapper;
    protected $handler;
    protected $logger;

    const BATCH_SIZE = 100;

    public function __construct(
        \Vendor\M2Migration\Model\ResourceModel\Source $source,
        \Vendor\M2Migration\Model\ResourceModel\Destination $destination,
        \Vendor\M2Migration\Model\Mapper\FieldMapper $mapper,
        \Vendor\M2Migration\Model\Handler\DataTransformer $handler,
        LoggerInterface $logger
    ) {
        $this->source = $source;
        $this->destination = $destination;
        $this->mapper = $mapper;
        $this->handler = $handler;
        $this->logger = $logger;
    }

    public function execute(): void
    {
        $offset = 0;

        do {
            $records = $this->source->fetchBatch('customer_entity', self::BATCH_SIZE, $offset);

            foreach ($records as &$record) {
                $record = $this->mapper->map('customer', $record);
                $record = $this->handler->transform($record);
            }

            if (!empty($records)) {
                $this->destination->save('customer_entity', $records);
                $this->logger->info("Migrated batch: $offset");
            }

            $offset += self::BATCH_SIZE;

        } while (!empty($records));
    }
}