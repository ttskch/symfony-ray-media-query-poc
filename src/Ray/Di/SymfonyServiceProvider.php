<?php

declare(strict_types=1);

namespace App\Ray\Di;

use Ray\Di\InjectionPointInterface;
use Ray\Di\ProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @template T of object
 *
 * @implements ProviderInterface<T>
 */
final readonly class SymfonyServiceProvider implements ProviderInterface
{
    public function __construct(
        private InjectionPointInterface $ip,
        private ContainerInterface $symfonyContainer,
    ) {
    }

    /**
     * @return T
     */
    public function get(): object
    {
        $parameter = $this->ip->getParameter();
        $attributes = $parameter->getAttributes(SymfonyService::class);
        $serviceId = $attributes[0]->newInstance()->serviceId;

        /** @var T $service */
        $service = $this->symfonyContainer->get($serviceId);

        return $service;
    }
}
