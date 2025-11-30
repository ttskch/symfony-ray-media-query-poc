<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Sale;
use App\Factory\SaleFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface SaleRepositoryInterface
{
    #[DbQuery('sale.find', factory: SaleFactory::class)]
    public function find(int $id): ?Sale;

    /**
     * @return list<Sale>
     */
    #[DbQuery('sale.findAll', factory: SaleFactory::class)]
    public function findAll(): array;

    /**
     * @return list<Sale>
     */
    #[DbQuery('sale.search', factory: SaleFactory::class)]
    public function search(string $date, ?int $team_id = null): array;

    #[DbQuery('sale.new')]
    public function new(string $date, int $amount, int $user_id): void;

    #[DbQuery('sale.edit')]
    public function edit(int $id, string $date, int $amount, int $user_id): void;

    #[DbQuery('sale.delete')]
    public function delete(int $id): void;
}
