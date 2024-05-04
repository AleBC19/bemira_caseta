<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Hal\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ApiTools\Hal\View\HalJsonStrategy;

class HalJsonStrategyFactory
{
    /**
     * @return HalJsonStrategy
     */
    public function __invoke(ContainerInterface $container)
    {
        return new HalJsonStrategy($container->get('Laminas\ApiTools\Hal\JsonRenderer'));
    }
}
