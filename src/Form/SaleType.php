<?php

declare(strict_types=1);

namespace App\Form;

use App\Form\Dto\SaleDto;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SaleType extends AbstractType
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $users = $this->userRepository->findAll();

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
            ->add('userId', ChoiceType::class, [
                'label' => 'User',
                'choices' => array_column($users, 'id', 'username'),
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SaleDto::class,
        ]);
    }
}
