<?php

declare(strict_types=1);

namespace App\Ray\MediaQuery\Factory;

use App\Entity\Sale;
use App\Entity\Team;
use App\Entity\User;
use App\Entity\UserTeamHistory;
use App\Ray\Di\SymfonyService;
use Doctrine\ORM\EntityManagerInterface;

final readonly class SaleFactory
{
    public function __construct(
        #[SymfonyService(EntityManagerInterface::class)]
        private EntityManagerInterface $em,
    ) {
    }

    public function factory(int $id, string $date, int $amount, string $user_json): Sale
    {
        $userArray = json_decode($user_json, true);

        $teamHistories = isset($userArray['team_histories'])
            ? array_map(fn (array $history) => $this->instantiateUserTeamHistory(
                id: $history['id'],
                user: $this->instantiateUser(id: $history['user_id'], username: '', teamHistories: []),
                team: isset($history['team_json'])
                    ? $this->instantiateTeam(id: $history['team_json']['id'], name: $history['team_json']['name'])
                    : $this->instantiateTeam(id: $history['team_id'], name: ''),
                fromDate: $history['from_date'] ? new \DateTimeImmutable($history['from_date']) : null,
                toDate: $history['to_date'] ? new \DateTimeImmutable($history['to_date']) : null,
            ), $userArray['team_histories'])
            : []
        ;

        $user = $this->instantiateUser(
            id: $userArray['id'],
            username: $userArray['username'],
            teamHistories: $teamHistories,
        );

        return $this->instantiateSale(
            id: $id,
            date: new \DateTimeImmutable($date),
            amount: $amount,
            user: $user,
        );
    }

    private function instantiateSale(int $id, \DateTimeImmutable $date, int $amount, User $user): Sale
    {
        $sale = (new Sale())
            ->setDate($date)
            ->setAmount($amount)
            ->setUser($user)
        ;
        (new \ReflectionProperty($sale, 'id'))->setValue($sale, $id);

        $this->em->persist($sale);

        return $sale;
    }

    /**
     * @phpstan-param array<UserTeamHistory> $teamHistories
     */
    private function instantiateUser(int $id, string $username, array $teamHistories): User
    {
        $user = (new User())->setUsername($username);
        foreach ($teamHistories as $teamHistory) {
            $user->addTeamHistory($teamHistory);
        }
        (new \ReflectionProperty($user, 'id'))->setValue($user, $id);

        $this->em->persist($user);

        return $user;
    }

    private function instantiateTeam(int $id, string $name): Team
    {
        $team = (new Team())->setName($name);
        (new \ReflectionProperty($team, 'id'))->setValue($team, $id);

        $this->em->persist($team);

        return $team;
    }

    private function instantiateUserTeamHistory(int $id, User $user, Team $team, ?\DateTimeImmutable $fromDate, ?\DateTimeImmutable $toDate): UserTeamHistory
    {
        $history = (new UserTeamHistory())
            ->setUser($user)
            ->setTeam($team)
            ->setFromDate($fromDate)
            ->setToDate($toDate)
        ;
        (new \ReflectionProperty($history, 'id'))->setValue($history, $id);

        $this->em->persist($history);

        return $history;
    }
}
