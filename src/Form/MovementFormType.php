<?php

namespace App\Form;

use App\Entity\Movement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class MovementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // ->add('movementChoice', ChoiceType::class, [
        //     'attr' => [
        //         'class' => 'form-control mb-3 form-select'
        //     ],
        //     'label' => 'Type de mouvement',
        //     'choices' => [
        //         'Dépot' => 'Dépot',
        //         'Retrait' => 'Retrait' 
        //         ]
        // ])
            ->add('movement', MoneyType::class, [
                'attr' => [
                    'class' => 'form-control mb-3'
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
                'placeholder' => 'jj/mm/aaaa',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control js-datepicker'
                ],
                'label' => 'Date'
            ])
            // ->add('user_id')

            // ->add('user_id', IntegerType::class, [
            //         'disabled' => true,
            //         'label' => 'ID de l\'utilisateur',
            //         // 'mapped' => false,
            //         'attr' => [
            //             'class' => 'form-control mb-3'
            //         ]
            //     ])

            // ->add('user_id', IntegerType::class, [
            //     // 'disabled' => true,
            //     'label' => 'ID de l\'utilisateur',
            //     // 'mapped' => false,
            //     'attr' => [
            //         'class' => 'form-control mb-3'
            //     ]
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Movement::class,
        ]);
    }
}
