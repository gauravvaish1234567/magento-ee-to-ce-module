<?php

namespace Vendor\M2Migration\Model\Mapper;

class FieldMapper
{
    protected $reader;

    public function __construct(\Vendor\M2Migration\Model\Reader\MapReader $reader)
    {
        $this->reader = $reader;
    }

    public function map($entity, $data)
    {
        $map = $this->reader->getMap($entity);
        $mapped = [];

        foreach ($map as $source => $target) {
            $mapped[$target] = $data[$source] ?? null;
        }

        return $mapped;
    }
}