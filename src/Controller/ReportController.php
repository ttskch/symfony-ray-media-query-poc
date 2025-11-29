<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Team;
use App\Entity\UserTeamHistory;
use App\Form\ReportSearchType;
use App\Repository\SaleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReportController extends AbstractController
{
    public function __construct(
        private readonly SaleRepository $saleRepository,
    ) {
    }

    #[Route(path: '/', name: 'home', methods: ['GET'])]
    public function home(): Response
    {
        return $this->redirectToRoute('report_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route(path: '/report/', name: 'report_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        $form = $this->createForm(ReportSearchType::class, options: [
            'method' => 'GET',
            'csrf_protection' => false,
        ]);
        $form->handleRequest($request);

        // default date
        if ($form->get('date')->getData() === null) {
            $form->get('date')->setData(new \DateTime('2025-01-01'));
        }

        $date = $form->get('date')->getData();
        $team = $form->get('team')->getData();
        assert($date instanceof \DateTimeInterface);
        assert($team instanceof Team || $team === null);

        $qb = $this->saleRepository->createQueryBuilder('s')
            ->leftJoin(UserTeamHistory::class, 'uth', Join::WITH, 's.user = uth.user')
            ->andWhere('s.date = :date')
            ->andWhere('uth.fromDate IS NULL OR uth.fromDate <= s.date')
            ->andWhere('uth.toDate IS NULL OR uth.toDate >= s.date')
            ->setParameter('date', $date, Types::DATE_MUTABLE)
        ;

        if ($team !== null) {
            $qb->andWhere('uth.team = :team')->setParameter('team', $team);
        }

        $sales = $qb->getQuery()->getResult();

        return $this->render('report/index.html.twig', [
            'sales' => $sales,
            'date' => $date,
            'form' => $form->createView(),
        ]);
    }
}
