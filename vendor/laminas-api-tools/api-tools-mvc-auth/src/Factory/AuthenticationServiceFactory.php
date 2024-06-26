<?php

declare(strict_types=1);

namespace Laminas\ApiTools\MvcAuth\Factory;

use Psr\Container\ContainerInterface;
use Laminas\Authentication\AuthenticationService;
use Laminas\Authentication\Storage\NonPersistent;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

class AuthenticationServiceFactory implements FactoryInterface
{
    /**
     * Create and return an AuthenticationService instance.
     *
     * @param string $requestedName
     * @param null|array $options
     * @return AuthenticationService
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        return new AuthenticationService($container->get(NonPersistent::class));
    }

    /**
     * Create and return an AuthenticationService instance (v2).
     *
     * Provided for backwards compatibility; proxies to __invoke().
     *
     * @return AuthenticationService
     */
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, AuthenticationService::class);
    }
}
