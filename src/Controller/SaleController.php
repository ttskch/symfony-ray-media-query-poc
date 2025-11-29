<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\UserTeamHistory;
use App\Form\Dto\SaleReportSearchDto;
use App\Form\SaleReportSearchType;
use App\Form\SaleType;
use App\Repository\SaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/sale', name: 'sale_')]
final class SaleController extends AbstractController
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly SaleRepository $saleRepository,
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

        $qb = $this->saleRepository->createQueryBuilder('s')
            ->leftJoin(UserTeamHistory::class, 'uth', Join::WITH, 's.user = uth.user')
            ->andWhere('s.date = :date')
            ->andWhere('uth.fromDate IS NULL OR uth.fromDate <= s.date')
            ->andWhere('uth.toDate IS NULL OR uth.toDate >= s.date')
            ->setParameter('date', $dto->date, Types::DATE_IMMUTABLE)
        ;

        if ($dto->team !== null) {
            $qb->andWhere('uth.team = :team')->setParameter('team', $dto->team);
        }

        $sales = $qb->getQuery()->getResult();

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
        $sale = new Sale();
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->persist($sale);
            $this->em->flush();

            $this->addFlash('success', 'The new sale was successfully created.');

            return $this->redirectToRoute('sale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale/new.html.twig', [
            'sale' => $sale,
            'form' => $form,
        ]);
    }

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Sale $sale): Response
    {
        return $this->render('sale/show.html.twig', [
            'sale' => $sale,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Sale $sale): Response
    {
        $form = $this->createForm(SaleType::class, $sale);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->em->flush();

            $this->addFlash('success', 'The sale was successfully updated.');

            return $this->redirectToRoute('sale_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('sale/edit.html.twig', [
            'sale' => $sale,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'delete', methods: ['DELETE'])]
    public function delete(Request $request, Sale $sale): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sale->getId(), $request->getPayload()->getString('_token'))) {
            $this->em->remove($sale);
            $this->em->flush();
        }

        return $this->redirectToRoute('sale_index', [], Response::HTTP_SEE_OTHER);
    }
}
