<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name' , TextType::class , [
                'label'=> 'Quel nom souhaitez-vous donner a votre addresse ? ',
                'attr'=>[
                    'placeholder'=>'Nommez votre addresse'
                ]
            ])
            ->add('firstname' , TextType::class , [
                'label'=> ' Prénom ',
                'attr'=>[
                    'placeholder'=>'Vote Prénom'
                ]
                ])
            ->add('lastname', TextType::class , [
                'label'=> 'Nom ',
                'attr'=>[
                    'placeholder'=>'Votre Nom'
                ]
                ])
            ->add('company', TextType::class , [
                'label'=> 'quel nom souhaitez-vous donner a votre addresse ? ',
                'attr'=>[
                    'placeholder'=>'(facultatif) Entrez le nom de Votre Société'
                ]
                ])

                ->add('phone', TelType::class, [
                    'label'=> 'Votre Téléphone ',
                    'attr'=>[
                        'placeholder'=>'Entrez votre téléphone'
                    ]
                    ])

                ->add('country', CountryType::class , [
                    'label'=> ' Pays ',
                    'attr'=>[
                        'placeholder'=>'Votre Pays'
                    ]
                    ])
            ->add('adress', TextType::class , [
                'label'=> ' Adresse ',
                'attr'=>[
                    'placeholder'=>'Votre Adresse'
                ]
                ])
            ->add('postal', TextType::class , [
                'label'=> ' Code Postal ',
                'attr'=>[
                    'placeholder'=>'Entrez Votre code Postal'
                ]
                ])
            ->add('city' , TextType::class , [
                'label'=> 'Ville ',
                'attr'=>[
                    'placeholder'=>'Entrez votre ville'
                ]
                ])

            ->add('submit',SubmitType::class,[
                'label'=>'Ajouter mon Adresse',
                'attr'=>[
                    'class'=>'btn-block btn-info '
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
