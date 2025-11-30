<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Team;
use Ray\MediaQuery\Annotation\DbQuery;

interface TeamRepositoryInterface
{
    #[DbQuery('team.find')]
    public function find(int $id): ?Team;

    /**
     * @return list<Team>
     */
    #[DbQuery('team.findAll')]
    public function findAll(): array;
}
