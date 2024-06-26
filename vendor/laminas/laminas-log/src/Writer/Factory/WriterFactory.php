<?php

declare(strict_types=1);

namespace Laminas\Log\Writer\Factory;

use Psr\Container\ContainerInterface;
use Laminas\ServiceManager\Exception\InvalidServiceException;
use Laminas\ServiceManager\FactoryInterface;
use Laminas\ServiceManager\ServiceLocatorInterface;

use function class_exists;
use function is_array;
use function is_string;
use function sprintf;

/**
 * Factory for instantiating classes with no dependencies or which accept a single array.
 *
 * The WriterFactory can be used for any class that:
 *
 * - has no constructor arguments;
 * - accepts a single array of arguments via the constructor.
 *
 * It replaces the "invokables" and "invokable class" functionality of the v2
 * service manager, and can also be used in v2 code for forwards compatibility
 * with v3.
 */
final class WriterFactory implements FactoryInterface
{
    /**
     * Options to pass to the constructor (when used in v2), if any.
     *
     * @param null|array
     */
    private $creationOptions;

    /**
     * @param array $creationOptions
     */
    public function __construct(?array $creationOptions = null)
    {
        if (is_array($creationOptions)) {
            $this->setCreationOptions($creationOptions);
        }
    }

    /**
     * Create an instance of the requested class name.
     *
     * @param string $requestedName
     * @param null|array $options
     * @return object
     */
    public function __invoke(ContainerInterface $container, $requestedName, ?array $options = null)
    {
        $options = (array) $options;

        $options = $this->populateOptions($options, $container, 'filter_manager', 'LogFilterManager');
        $options = $this->populateOptions($options, $container, 'formatter_manager', 'LogFormatterManager');

        return new $requestedName($options);
    }

    /**
     * Populates the options array with the correct container value.
     *
     * @param array $options
     * @param string $name
     * @param string $defaultService
     * @return array
     */
    private function populateOptions(array $options, ContainerInterface $container, $name, $defaultService)
    {
        if (isset($options[$name]) && is_string($options[$name])) {
            $options[$name] = $container->get($options[$name]);
            return $options;
        }

        if (! isset($options[$name]) && $container->has($defaultService)) {
            $options[$name] = $container->get($defaultService);
            return $options;
        }

        return $options;
    }

    /**
     * Create an instance of the named service.
     *
     * First, it checks if `$canonicalName` resolves to a class, and, if so, uses
     * that value to proxy to `__invoke()`.
     *
     * Next, if `$requestedName` is non-empty and resolves to a class, this
     * method uses that value to proxy to `__invoke()`.
     *
     * Finally, if the above each fail, it raises an exception.
     *
     * The approach above is performed as version 2 has two distinct behaviors
     * under which factories are invoked:
     *
     * - If an alias was used, $canonicalName is the resolved name, and
     *   $requestedName is the service name requested, in which case $canonicalName
     *   is likely the qualified class name;
     * - Otherwise, $canonicalName is the normalized name, and $requestedName
     *   is the original service name requested (typically the qualified class name).
     *
     * @param null|string $canonicalName
     * @param null|string $requestedName
     * @return object
     * @throws InvalidServiceException
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $canonicalName = null, $requestedName = null)
    {
        if (is_string($canonicalName) && class_exists($canonicalName)) {
            return $this($serviceLocator->getServiceLocator(), $canonicalName, $this->creationOptions);
        }

        if (is_string($requestedName) && class_exists($requestedName)) {
            return $this($serviceLocator->getServiceLocator(), $requestedName, $this->creationOptions);
        }

        throw new InvalidServiceException(sprintf(
            '%s requires that the requested name is provided on invocation; '
            . 'please update your tests or consuming container',
            self::class
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function setCreationOptions(array $creationOptions)
    {
        $this->creationOptions = $creationOptions;
    }
}
