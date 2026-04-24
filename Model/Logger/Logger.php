<?php

namespace Vendor\M2Migration\Model\Logger;

class Logger
{
    public function info($message)
    {
        file_put_contents(BP . '/var/log/m2migration.log', $message . PHP_EOL, FILE_APPEND);
    }
}