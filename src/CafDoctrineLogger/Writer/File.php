<?php

namespace CafDoctrineLogger\Writer;

use Zend\Log\Writer\Stream;

class File extends Stream
{

    public function __construct(array $config)
    {
        $filePath = $config['logPath'] . '/' . $config['fileName'];
        parent::__construct($filePath);
    }
}
