<?php

declare(strict_types=1);

namespace App\Ray\Di;

use Doctrine\ORM\EntityManagerInterface;
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
                interfaceDir: __DIR__.'/../MediaQuery/Query',
                sqlDir: __DIR__.'/../../../sql',
            ),
        );

        $this->install(
            new AuraSqlModule($this->dsn),
        );

        // Bind Symfony services to SymfonyServiceProvider
        $this->bind(EntityManagerInterface::class)->toProvider(SymfonyServiceProvider::class);
    }

    public function integrateWithSymfony(ContainerInterface $symfonyContainer, Injector $injector): void
    {
        $queryInterfaces = ClassesInDirectories::list(__DIR__.'/../MediaQuery/Query');

        // register all query interfaces to Symfony container
        foreach ($queryInterfaces as $queryInterface) {
            $symfonyContainer->set($queryInterface, $injector->getInstance($queryInterface));
        }
    }
}
