<?php

namespace CafDoctrineLogger\Writer;

use Zend\Log\Writer\Stream;

class File extends Stream
{

    public function __construct(array $config)
    {

        $interval = $config['fileInterval'];

        $name = floor(time() / $interval);
        $fileName = str_replace('%name%', $name, $config['fileNameTemplate']);
        $filePath = $config['logPath'] . '/' . $fileName;

        parent::__construct($filePath);
    }
}
