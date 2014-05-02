<?php

namespace CafDoctrineLogger;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Zend\Log\Logger;

class Subscriber implements EventSubscriber
{

    private $logger;

    public function __construct(Logger $logger)
    {
        $this->logger = $logger;
    }

    public function getSubscribedEvents()
    {
        return array(
            'postPersist',
            'postUpdate',
            'postRemove'
        );
    }

    public function __call($event, $args)
    {
        list($eventArgs) = $args;

        if (!in_array($event, $this->getSubscribedEvents())) {
            return;
        }

        $entity = $eventArgs->getEntity();
        if (!$entity instanceof Loggable) {
            return;
        }

        $entityClass = array_pop(explode('\\', get_class($entity)));

        $data = json_encode(array(
            'timestamp' => (new \DateTime())->format(DATE_ISO8601),
            'event' => $event,
            'entity' => $entityClass,
            'data' => $entity->toArray()
        ));

        $this->logger->log(Logger::INFO, $data);
    }
}
