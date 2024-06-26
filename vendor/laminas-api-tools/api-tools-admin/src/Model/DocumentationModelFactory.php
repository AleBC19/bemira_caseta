<?php

declare(strict_types=1);

namespace Laminas\ApiTools\Admin\Model;

use Psr\Container\ContainerInterface;
use Laminas\ApiTools\Configuration\ConfigResourceFactory;
use Laminas\ApiTools\Configuration\ModuleUtils;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function sprintf;

class DocumentationModelFactory implements FactoryInterface
{
    /**
     * Create and return a DocumentationModel instance.
     *
     * @param string $requestedName
     * @param null|array $options
     * @return DocumentationModel
     * @throws ServiceNotCreatedException
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        if (
            ! $container->has(ConfigResourceFactory::class)
            && ! $container->has(\ZF\Configuration\ConfigResourceFactory::class)
        ) {
            throw new ServiceNotCreatedException(sprintf(
                '%s requires that the %s service be present; service not found',
                DocumentationModel::class,
                ConfigResourceFactory::class
            ));
        }
        return new DocumentationModel(
            $container->has(ConfigResourceFactory::class)
                ? $container->get(ConfigResourceFactory::class)
                : $container->get(\ZF\Configuration\ConfigResourceFactory::class),
            $container->get(ModuleUtils::class)
        );
    }

    /**
     * Create and return a DocumentationModel instance (v2).
     *
     * Provided for backwards compatibility; proxies to __invoke().
     *
     * @return DocumentationModel
     */
    public function createService(ServiceLocatorInterface $container)
    {
        return $this($container, DocumentationModel::class);
    }
}
