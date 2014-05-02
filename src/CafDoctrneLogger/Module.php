<?php

namespace CafDoctrineLogger;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        (new ModuleRouteListener())->attach($e->getApplication()->getEventManager());

        $sm = $e->getApplication()->getServiceManager();

        $evs = new EventListener\EntityChange($sm->get('entity.logger'));
        $sm->get('em')->getEventManager()->addEventSubscriber($evs);
    }

    public function getConfig()
    {
        return array();
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'entity.logger' => function ($sm) {
                    $logger = new Logger;

                    $logPath = getcwd() . '/data/queries';
                    if (!is_dir($logPath)) {
                        throw new \RuntimeException('Log directory not found: ' . $logPath);
                    }

                    $logFile = $logPath . '/' . floor(time() / 60) . '.log';
                    $writer = new StreamLogger($logFile);

                    $formatter = new LoggerFormatter('%message%');
                    $writer->setFormatter($formatter);

                    $logger->addWriter($writer);
                    return $logger;
                }
            ),
            'aliases' => array()
        );
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
