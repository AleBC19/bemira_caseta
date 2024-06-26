<?php

namespace Laminas\ApiTools\Configuration\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ModuleUtils;

class ModuleUtilsFactory
{
    /**
     * @return ModuleUtils
     */
    public function __invoke(ContainerInterface $container)
    {
        return new ModuleUtils($container->get('ModuleManager'));
    }
}
