<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Dto\SaleReportSearchDto;
use App\Repository\TeamRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleReportSearchType extends AbstractType
{
    public function __construct(
        private readonly TeamRepositoryInterface $teamRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $teams = $this->teamRepository->findAll();

        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'attr' => [
                    'min' => '2025-01-01',
                    'max' => '2025-01-31',
                ],
                'empty_data' => '2025-01-01',
            ])
            ->add('teamId', ChoiceType::class, [
                'required' => false,
                'label' => 'Team',
                'choices' => array_column($teams, 'id', 'name'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaleReportSearchDto::class,
        ]);
    }
}
