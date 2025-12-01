<?php

declare(strict_types=1);

namespace App\Ray\MediaQuery;

use Ray\Di\InjectorInterface;
use Ray\MediaQuery\FetchInjectionFactory;
use Ray\MediaQuery\FetchInterface;

/**
 * @see FetchInjectionFactory
 */
final readonly class SymfonyFetch implements FetchInterface
{
    public function __construct(
        private object $factory,
    ) {
    }

    public function fetchAll(\PDOStatement $pdoStatement, InjectorInterface $injector): array
    {
        $originalFetch = new FetchInjectionFactory(['', ''], 'factory');
        $fetchFactoryMethod = (new \ReflectionMethod($originalFetch, 'fetchFactory'));

        /** @var array<mixed> $result */
        $result = $fetchFactoryMethod->invokeArgs($originalFetch, [$pdoStatement, $this->factory]);

        return $result;
    }
}
