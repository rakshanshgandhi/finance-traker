<?php

namespace App\Form;

use App\Entity\Transaction;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class TransactionFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('description')
            ->add('amount')
            ->add('createdAt', DateType::class, [
                'widget' => 'single_text', // Use single input field for date
                'html5' => true, // Enable HTML5 date input
                'attr' => [
                    'class' => 'w-full px-4 py-2 border border-[#e6e6e6] rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500',
                ]
            ])
            ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Transaction::class,
        ]);
    }
}
