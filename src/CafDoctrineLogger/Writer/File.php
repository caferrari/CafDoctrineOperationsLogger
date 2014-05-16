<?php

namespace CafDoctrineLogger\Writer;

use Zend\Log\Writer\Stream;

class File extends Stream
{

    protected $filePath;

    public function __construct(array $config)
    {
        $filePath = $config['logPath'] . '/' . $config['fileName'];
        $this->filePath = $filePath;
        parent::__construct($filePath);
    }

    public function shutdown()
    {
        parent::shutdown();

        $size = filesize($this->filePath);

        if (0 === $size) {
            unlink($this->filePath);
        }
    }
}
