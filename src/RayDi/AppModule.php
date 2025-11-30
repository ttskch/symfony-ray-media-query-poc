<?php

declare(strict_types=1);

namespace App\RayDi;

use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;
use Ray\MediaQuery\ClassesInDirectories;
use Ray\MediaQuery\MediaQuerySqlModule;
use Symfony\Component\DependencyInjection\Attribute\Autoconfigure;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\ContainerInterface;

#[Autoconfigure(public: true)]
final class AppModule extends AbstractModule
{
    public function __construct(
        #[Autowire(env: 'resolve:DATABASE_URL')]
        private readonly string $dsn,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->install(
            new MediaQuerySqlModule(
                interfaceDir: __DIR__.'/../Repository',
                sqlDir: __DIR__.'/../../sql',
            ),
        );

        $this->install(
            new AuraSqlModule($this->dsn),
        );
    }

    public function registerServices(ContainerInterface $container): void
    {
        $injector = new Injector($this);
        $repositories = ClassesInDirectories::list(__DIR__.'/../Repository');

        foreach ($repositories as $repository) {
            $container->set($repository, $injector->getInstance($repository));
        }
    }
}
