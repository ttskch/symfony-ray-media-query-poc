<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\Dto\SaleDto;
use App\Form\Dto\SaleReportSearchDto;
use App\Form\SaleReportSearchType;
use App\Form\SaleType;
use App\Repository\SaleRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/sale', name: 'sale_')]
final class SaleController extends AbstractController
{
    public function __construct(
        private readonly SaleRepositoryInterface $saleRepository,
    ) {
    }

    #[Route(path: '/report', name: 'report', methods: ['GET'])]
    public function report(Request $request): Response
    {
        $dto = new SaleReportSearchDto();
        $form = $this->createForm(SaleReportSearchType::class, $dto, [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);

        assert($dto->date !== null);
        $sales = $this->saleRepository->search($dto->date->format('Y-m-d'), $dto->teamId);

        return $this->render('sale/report.html.twig', [
            'sales' => $sales,
            'date' => $dto->date,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('sale/index.html.twig', [
            'sales' => $this->saleRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'new', methods: ['GET', 'POST'])]
    public function new(Request $request): Response
    {
        $dto = new SaleDto();
        $form = $this->createForm(SaleType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            assert($dto->date !== null && $dto->amount !== null && $dto->userId !== null);
            $this->saleRepository->new(
                date: $dto->date->format('Y-m-d'),
                amount: $dto->amount,
                user_id: $dto->userId,
            );

            $this->addFlash('success', 'The new sale was successfully created.');

            return $this->redirectToRoute('sale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(int $id): Response
    {
        $sale = $this->saleRepository->find($id);

        return $this->render('sale/show.html.twig', [
            'sale' => $sale,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $id): Response
    {
        $sale = $this->saleRepository->find($id);

        if ($sale === null) {
            throw new NotFoundHttpException();
        }

        $dto = new SaleDto(
            date: $sale->date,
            amount: $sale->amount,
            userId: $sale->user->id,
        );
        $form = $this->createForm(SaleType::class, $dto);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            assert($dto->date !== null && $dto->amount !== null && $dto->userId !== null);
            $this->saleRepository->edit(
                id: $id,
                date: $dto->date->format('Y-m-d'),
                amount: $dto->amount,
                user_id: $dto->userId,
            );

            $this->addFlash('success', 'The sale was successfully updated.');

            return $this->redirectToRoute('sale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale/edit.html.twig', [
            'sale' => $sale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, int $id): Response
    {
        $sale = $this->saleRepository->find($id);

        if ($sale === null) {
            throw new NotFoundHttpException();
        }

        if ($this->isCsrfTokenValid('delete'.$sale->id, $request->getPayload()->getString('_token'))) {
            $this->saleRepository->delete($id);
        }

        return $this->redirectToRoute('sale_index', [], Response::HTTP_SEE_OTHER);
    }
}
