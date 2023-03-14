<?php

namespace App\Form;

use App\Entity\Movement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class MovementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('movement', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Mouvement'    
            ])
            ->add('place', ChoiceType::class, [
                'attr' => [
                    'class' => 'form-control mb-3 form-select'
                ],
                'label' => 'Endroit',
                'choices' => [
                    'Choisir (facultatif)' => ' ',
                    'Betclic' => 'Betclic',
                    'Winamax' => 'Winamax',
                    'FDJ' => 'FDJ' 
                    ]
            ])
            ->add('date', DateType::class, [
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control js-datepicker'
                ],
                'label' => 'Date'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movement::class,
        ]);
    }
}
