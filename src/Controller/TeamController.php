<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\TeamRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/team', name: 'team_')]
final class TeamController extends AbstractController
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository,
    ) {
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $team = $this->teamRepository->find($id);

        if ($team === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('team/show.html.twig', [
            'team' => $team,
        ]);
    }
}
