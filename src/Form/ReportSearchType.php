<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Team;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class ReportSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'attr' => [
                    'min' => '2025-01-01',
                    'max' => '2025-01-31',
                ],
            ])
            ->add('team', EntityType::class, [
                'label' => 'Team',
                'required' => false,
                'class' => Team::class,
            ])
        ;
    }
}
