<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\Sale;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date', DateType::class, [
                'label' => 'Date',
                'attr' => [
                    'min' => '2025-01-01',
                    'max' => '2025-01-31',
                    'autofocus' => true,
                ],
            ])
            ->add('amount', NumberType::class, [
                'label' => 'Amount',
                'html5' => true,
            ])
            ->add('user', EntityType::class, [
                'class' => User::class,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sale::class,
        ]);
    }
}
