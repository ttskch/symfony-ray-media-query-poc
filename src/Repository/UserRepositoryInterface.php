<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Factory\UserFactory;
use Ray\MediaQuery\Annotation\DbQuery;

interface UserRepositoryInterface
{
    #[DbQuery('user.find', factory: UserFactory::class)]
    public function find(int $id): ?User;

    /**
     * @return list<User>
     */
    #[DbQuery('user.findAll', factory: UserFactory::class)]
    public function findAll(): array;
}
