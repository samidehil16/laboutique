<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('lastname',TextType::class,[
                'label'=> 'Votre Nom',
                'constraints'=> new Length([
                    'min'=> 2,
                    'max'=>30
                ]),
                'attr'=>['placeholder'=>'Merci de saisir votre Nom']
            ])
            ->add('firstname', TextType::class, [
                'label'=>'Votre prénom',
                'constraints'=> new Length([
                    'min'=> 2,
                    'max'=>30
                ]),
                'attr' =>['placeholder'=> 'Merci de saisir votre Prénom']
            ])
            ->add('email', EmailType::class, [
                'label'=>'Votre Email',
                'constraints'=> new Length([
                    'min'=> 2,
                    'max'=>30
                ]), 
                    'attr'=> [
                        'placeholder'=>'Merci de saisir votre Email'
                    ]
                
            ])
            ->add('password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'constraints'=> new Length([
                    'min'=> 8,
                    'max'=>30
                ]),
                'invalid_message'=>'le mot de passe est incorrect',
                'label'=>'Votre mot de passe',
                'required'=>true ,
                'first_options'=>['label'=>'votre mot de passe'],
                'second_options'=>['label'=>'confirmez votre mot de passe']
            ])

            ->add('submit', SubmitType::class ,[
                'label'=> "s'inscrire"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
