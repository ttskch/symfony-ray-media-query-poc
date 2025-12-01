<?php

declare(strict_types=1);

namespace App\Ray\MediaQuery;

use Ray\MediaQuery\Annotation\DbQuery;
use Ray\MediaQuery\FetchFactoryInterface;
use Ray\MediaQuery\FetchInterface;

/**
 * @see \Ray\MediaQuery\MediaQueryDbModule
 * @see \Ray\MediaQuery\FetchFactory
 */
final class SymfonyFetchFactory implements FetchFactoryInterface
{
    /**
     * @var iterable<object>
     */
    private iterable $factories;

    /**
     * @param iterable<object> $factories
     */
    public function setFactories(
        iterable $factories,
    ): void {
        $this->factories = $factories;
    }

    public function factory(DbQuery $dbQuery, ?string $entity, \ReflectionNamedType|\ReflectionUnionType|null $returnType): FetchInterface
    {
        foreach ($this->factories as $factory) {
            if ($dbQuery->factory === $factory::class) {
                return new SymfonyFetch($factory);
            }
        }

        throw new \LogicException(\sprintf('Factory "%s" not found.', $dbQuery->factory));
    }
}
