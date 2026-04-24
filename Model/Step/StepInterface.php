<?php
namespace Vendor\M2Migration\Model\Step;

interface StepInterface
{
    public function execute(): void;
}