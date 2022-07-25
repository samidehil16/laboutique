<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('new_password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'constraints'=> new Length([
                    'min'=> 8,
                    'max'=>30
                ]),
                'invalid_message'=>'le mot de passe est incorrect',
                'label'=>'Votre mot de passe',
                'required'=>true ,
                'first_options'=>['label'=>'Votre nouveau mot de passe'],
                'second_options'=>['label'=>'Confirmez votre mot de passe']
            ])

            ->add('submit', SubmitType::class ,[
                'label'=> "Mettre à jour",
                'attr'=>[
                    'class'=>'btn-block btn-info'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
