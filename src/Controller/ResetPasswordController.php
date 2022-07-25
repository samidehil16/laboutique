<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager;

    public function __construct( EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/reset/forget_password', name: 'reset_password')]
    public function index( Request $request): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        if ($request->get('email')) {
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user) {
                $reset_password = new ResetPassword();
                $reset_password->setUser($user);
                $reset_password->setToken(uniqid());
                $reset_password->setCreatedAt( new DateTimeImmutable());
                $this->entityManager->persist($reset_password);
                $this->entityManager->flush();

                $url= $this->generateUrl('update_reset_password',['token' => $reset_password->getToken()]);

                $contenu = "Bonjour".$user->getFirstname()."<br/> Vous avez demandé a réinitialiser votre
                mot de passe sur le site laBoutique<br/> <br/>"."Merci de bien vouloir cliquer sur le lien suivant
                pour <a href='".$url."'> mettre a jour votre mot de passe </a>.";
                
                $mail = new Mail();
                $mail->send($user->getEmail(),$user->getFirstname(),'Réinitialiser votre mot de passe 
                sur laBoutiqueOfficiel',$contenu);

                $this->addFlash('notice','Vous allez recevoir un mail dans quelques secondes.');
            }else{
                $this->addFlash('notice','Cette adresse Email est inconnu.');
            }
        }
        return $this->render('reset_password/index.html.twig', [
            
        ]);
    }

    #[Route('/reset/password/{token}', name: 'update_reset_password')]
    public function update( $token, Request $request, UserPasswordHasherInterface $hasher): Response
    {
       $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
      
       if (!$reset_password) {
           return $this->redirectToRoute('reset_password');
       }


       $now= new DateTime();
       if ($now > $reset_password->getCreatedAt()->modify('+3hour')) {
           $this->addFlash('notice','Votre demande de mot de passe a expiré. Merci
           de la renouveler.');
           return $this->redirectToRoute('reset_password');
       }
        
        $form = $this->createForm(ResetPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $new_pwd = $form->get('new_password')->getData();
            $password = $hasher->hashPassword($reset_password->getUser(),$new_pwd);
            $reset_password->getUser()->setPassword($password);
            $this->entityManager->flush();

            $this->addFlash('notice','Votre mot de passe a bien étais mis à jour.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('reset_password/update.html.twig', [
            'form'=>$form->createView()
        ]);
    }
}
