<?php

declare(strict_types=1);

namespace App\Ray\Di;

use App\Ray\MediaQuery\SymfonyFetchFactory;
use Ray\AuraSqlModule\AuraSqlModule;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;
use Ray\Di\Scope;
use Ray\MediaQuery\ClassesInDirectories;
use Ray\MediaQuery\FetchFactoryInterface;
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

        // override the binding for FetchFactoryInterface and make it singleton
        $this->bind(FetchFactoryInterface::class)->to(SymfonyFetchFactory::class)->in(Scope::SINGLETON);
    }

    public function integrateWithSymfony(ContainerInterface $symfonyContainer, Injector $injector): void
    {
        $queryInterfaces = ClassesInDirectories::list(__DIR__.'/../MediaQuery/Query');
        $factoryClasses = ClassesInDirectories::list(__DIR__.'/../MediaQuery/Factory');

        // register all query interfaces to Symfony container
        foreach ($queryInterfaces as $queryInterface) {
            $symfonyContainer->set($queryInterface, $injector->getInstance($queryInterface));
        }

        // inject factories into FetchFactory
        $factories = array_map(fn ($factoryClass) => $symfonyContainer->get($factoryClass), iterator_to_array($factoryClasses));
        $fetchFactory = $injector->getInstance(FetchFactoryInterface::class);
        assert($fetchFactory instanceof SymfonyFetchFactory);
        $fetchFactory->setFactories($factories);
    }
}
