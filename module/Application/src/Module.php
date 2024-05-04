<?php

declare(strict_types=1);

namespace Application;

use Application\Library\Listener\MvcListener;
use Laminas\Mvc\MvcEvent;

class Module
{
    /**
     * @return array<string,mixed>
     */
    public function getConfig()
    {
        return include __DIR__ . '/../config/module.config.php';
    }

    public function onBootstrap(MvcEvent $event) : void
    {
        // Get application instance.
        $application = $event->getApplication();
        
        // Create and register MVC listener.
        $listener = new MvcListener();
        $listener->attach($application->getEventManager());
    }
}
