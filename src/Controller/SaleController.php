<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Sale;
use App\Entity\UserTeamHistory;
use App\Form\Dto\SaleReportSearchDto;
use App\Form\SaleReportSearchType;
use App\Repository\SaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route(path: '/sale', name: 'sale_')]
final class SaleController extends AbstractController
{
    public function __construct(
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

    #[Route(path: '/{id}', name: 'show', methods: ['GET'])]
    public function show(Sale $sale): Response
    {
        return $this->render('sale/show.html.twig', [
            'sale' => $sale,
        ]);
    }
}
