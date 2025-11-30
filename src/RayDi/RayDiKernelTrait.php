<?php

declare(strict_types=1);

namespace App\RayDi;

use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * @property ContainerInterface $container
 */
trait RayDiKernelTrait
{
    protected function initializeContainer(): void
    {
        parent::initializeContainer();

        $appModule = $this->container->get(AppModule::class);
        $appModule->registerServices($this->container);
    }
}
