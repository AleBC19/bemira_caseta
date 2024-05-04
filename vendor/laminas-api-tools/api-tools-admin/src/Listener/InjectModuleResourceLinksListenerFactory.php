<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Admin\Listener;

use Psr\Container\ContainerInterface;

class InjectModuleResourceLinksListenerFactory
{
    /**
     * @return InjectModuleResourceLinksListener
     */
    public function __invoke(ContainerInterface $container)
    {
        return new InjectModuleResourceLinksListener(
            $container->get('ViewHelperManager')
        );
    }
}
