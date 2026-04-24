<?php

namespace Vendor\M2Migration\Model\Handler;

class DataTransformer
{
    public function transform($data)
    {
        // Remove Commerce-specific fields
        unset($data['reward_points']);
        unset($data['customer_balance']);

        return $data;
    }
}