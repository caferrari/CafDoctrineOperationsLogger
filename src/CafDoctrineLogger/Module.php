<?php

namespace CafDoctrineLogger;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream as StreamLogger;

class Module
{

    public function onBootstrap(MvcEvent $e)
    {

        $evm = $e->getApplication()->getEventManager();

        (new ModuleRouteListener())->attach($evm);

        $sm = $e->getApplication()->getServiceManager();

        $config = $sm->get('config')['doctrine.logger'];
        if (!count($config['writers'])) {
            return;
        }

        $logger = $sm->get('doctrine.entity.logger');
        $evs = new Subscriber($logger, $config['events']);
        $sm->get('em')->getEventManager()->addEventSubscriber($evs);

        $evm->attach(MvcEvent::EVENT_FINISH, function () use ($logger) {
            $logger->__destruct();
        });
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'doctrine.entity.logger' => function ($sm) {
                    $logger = new Logger;
                    $config = $sm->get('config')['doctrine.logger'];
                    foreach ($config['writers'] as $writer => $options) {
                        $writer = $sm->get($writer);
                        if (!empty($options['formatter'])) {
                            $f = $sm->get($options['formatter']);
                            $writer->setFormatter($f);
                        }
                        $logger->addWriter($writer);
                    }

                    return $logger;
                },
                'CafDoctrineLogger\Writer\File' => function ($sm) {
                    $config = $sm->get('config')['doctrine.logger']['writers'];
                    return new Writer\File($config['CafDoctrineLogger\Writer\File']);
                }
            ),
            'invokables' => array(
                'CafDoctrineLogger\Formatter' => 'CafDoctrineLogger\Formatter'
            ),
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
