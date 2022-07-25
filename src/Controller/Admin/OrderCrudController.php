<?php

namespace App\Controller\Admin;


use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class OrderCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;
    private $entityManager;
    

    public function __construct( EntityManagerInterface $entityManager, AdminUrlGenerator $adminUrlGenerator)
    {
        $this->entityManager = $entityManager;
        $this->adminUrlGenerator = $adminUrlGenerator;

    }

    public static function getEntityFqcn(): string
    {
        return Order::class;
    }

    public function configureActions(Actions $actions): Actions
    {
     $updatePreparation = Action::new('updatePreparation','Preparation en cours','fas fa-box-open')->linkToCrudAction('updatePreparation');
     $updateDelivery = Action::new('updateDelivery','Livraison en cours','fas fa-truck')->linkToCrudAction('updateDelivery');

      return $actions
      ->add('detail',$updatePreparation)
      ->add('detail',$updateDelivery)
      ->add('index','detail'); 

    }

    public function updatePreparation(AdminContext $context){

        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice',"<span style='color: green;'> <strong> La commande".$order->getReference()." a bien été mise a jour.</strong> </span>");

        $url = $this->adminUrlGenerator->setController(OrderCrudController::class)
            ->setAction(Action::INDEX)
            ->generateUrl();

        

        return $this->redirect($url);
    }

    public function updateDelivery(AdminContext $context){

        $order = $context->getEntity()->getInstance();
        $order->setState(3);
        $this->entityManager->flush();

        $this->addFlash('notice',"<span style='color: orange;'> <strong> La commande".$order->getReference()." est bien en cours de livraison.</strong> </span>");

        $url = $this->adminUrlGenerator->setController(OrderCrudController::class)
        ->setAction(Action::INDEX)
        ->generateUrl();

    

        return $this->redirect($url);
    }

    public function configureCrud(Crud $crud): Crud
    {
       return $crud->setDefaultSort(['id'=>'DESC']) ;
    }
    
    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id'),
            DateTimeField::new('createdAt','Passé le'),
            TextField::new('user.getFullName','Utilisateur'),
            TextEditorField::new('delivery','adresse de livraison')->onlyOnDetail(),
            MoneyField::new('total','total produit')->setCurrency('EUR'),
            TextField::new('carrierName','Transporteur'),
            MoneyField::new('carrierPrice','frais de port')->setCurrency('EUR'),
            ChoiceField::new('state','Etat')->setChoices([
                'Non Payée'=>0,
                'Payée'=>1,
                'Préparation en cours'=>2,
                'Livraison en cours'=>3

            ]),
            ArrayField::new('orderDetails' ,'Produits Achetées')
        ];
    }
    
}
