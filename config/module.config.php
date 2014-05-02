<?php

return array(
    'doctrine.logger' => array(
        'writers' => array(
            // 'CafDoctrineLogger\Writer\File' => array(
            //     'formatter' => 'CafDoctrineLogger\Formatter',
            //     'logPath' => getcwd() . '/data/queries',
            //     'fileInterval' => 60,
            //     'fileNameTemplate' => '%name%.log'
            // )
        ),
        'events' => array(
            'postPersist',
            'postUpdate',
            'postRemove'
        )
    )
);
