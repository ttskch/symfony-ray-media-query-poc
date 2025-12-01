<?php

declare(strict_types=1);

namespace App\Ray\Di;

use Ray\Di\InjectionPointInterface;
use Ray\Di\ProviderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @template T of object
 * @implements ProviderInterface<T>
 */
final class SymfonyServiceProvider implements ProviderInterface
{
    public function __construct(
        private readonly InjectionPointInterface $ip,
        private readonly ContainerInterface $symfonyContainer,
    ) {
    }

    /**
     * @return T
     */
    public function get(): object
    {
        // InjectionPointから#[SymfonyService]属性を取得
        $parameter = $this->ip->getParameter();
        $attributes = $parameter->getAttributes(SymfonyService::class);

        if ($attributes === []) {
            throw new \LogicException(sprintf('Parameter $%s requires #[SymfonyService] attribute', $parameter->getName()));
        }

        $serviceAttr = $attributes[0]->newInstance();
        $serviceId = $serviceAttr->serviceId;

        // Symfonyコンテナから取得
        $service = $this->symfonyContainer->get($serviceId);
        /** @var T $service */
        return $service;
    }
}
