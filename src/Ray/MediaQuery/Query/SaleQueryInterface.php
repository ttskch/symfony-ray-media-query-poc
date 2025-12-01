<?php

declare(strict_types=1);

namespace App\Ray\MediaQuery\Query;

use App\Entity\Sale;
use App\Ray\MediaQuery\Factory\SaleFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface SaleQueryInterface
{
    /**
     * @return list<Sale>
     */
    #[DbQuery('sale.search', factory: SaleFactory::class)]
    public function search(string $date, ?int $team_id = null): array;
}
