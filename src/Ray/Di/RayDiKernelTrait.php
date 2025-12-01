<?php

declare(strict_types=1);

namespace App\Ray\Di;

use Ray\Di\Injector;
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
        $injector = $this->container->get(Injector::class);

        $appModule->integrateWithSymfony($this->container, $injector);
    }
}
