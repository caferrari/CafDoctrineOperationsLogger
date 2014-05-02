<?php

namespace CafDoctrineLogger;

use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;

use Zend\Log\Logger;

class Subscriber implements EventSubscriber
{

    private $logger;
    private $events;

    public function __construct(Logger $logger, array $events)
    {
        $this->logger = $logger;
        $this->events = $events;
    }

    public function getSubscribedEvents()
    {
        return $this->events;
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
