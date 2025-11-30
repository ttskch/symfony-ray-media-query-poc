<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/user', name: 'user_')]
final class UserController extends AbstractController
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $user = $this->userRepository->find($id);

        if ($user === null) {
            throw new NotFoundHttpException();
        }

        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }
}
