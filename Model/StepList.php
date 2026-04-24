<?php

namespace Vendor\M2Migration\Model;

use Vendor\M2Migration\Model\Step\StepInterface;

class StepList
{
    protected $steps;

    public function __construct(array $steps = [])
    {
        foreach ($steps as $step) {
            if (!$step instanceof StepInterface) {
                throw new \InvalidArgumentException(
                    'All steps must implement StepInterface'
                );
            }
        }

        $this->steps = $steps;
    }

    public function executeAll(): void
    {
        foreach ($this->steps as $step) {
            $step->execute();
        }
    }
}