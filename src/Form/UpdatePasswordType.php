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

class UpdatePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',EmailType::class,[
              'disabled'=>true,
              'label'=>'Mon Adresse Email' 
            ])
            ->add('firstname',TextType::class,[
                'disabled'=>true ,
                'label'=>'Mon Prènom'
            ])
            ->add('lastname', TextType::class,[
                'disabled'=> true ,
                'label'=> 'Mon Nom'
            ])
            ->add('old_password',PasswordType::class,[
                'label'=>'Mon Mot de Passe Actuel',
                'mapped'=>false,
                'attr'=>[
                    'placeholder'=>'veuillez saisir votre mot de passe actuel'
                ]
            ])
            ->add('new_password', RepeatedType::class,[
                'type'=> PasswordType::class,
                'constraints'=> new Length([
                    'min'=> 8,
                    'max'=>30
                ]),
                'mapped'=>false,
                'invalid_message'=>'le mot de passe est incorrect',
                'label'=>'Mon Nouveau mot de passe',
                'required'=>true ,
                'first_options'=>['label'=>'Votre nouveau Mot de Passe'],
                'second_options'=>['label'=>'Merci de confirmez votre nouveau Mot de Passe']
            ])
            ->add('submit', SubmitType::class ,[
                'label'=> "Mettre a jour"
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
