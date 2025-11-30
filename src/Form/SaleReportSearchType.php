<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Team;
use App\Form\Dto\SaleReportSearchDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleReportSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'input' => 'datetime_immutable',
                'attr' => [
                    'min' => '2025-01-01',
                    'max' => '2025-01-31',
                ],
            ])
            ->add('team', EntityType::class, [
                'required' => false,
                'label' => 'Team',
                'class' => Team::class,
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
