<?php

return array(
    'doctrine.logger' => array(
        'writers' => array(),
        'events' => array(
            'postPersist',
            'postUpdate',
            'postRemove'
        )
    )
);
