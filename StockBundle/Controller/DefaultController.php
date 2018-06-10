<?php

namespace StockBundle\Controller;


use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\EntityManager;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use StockBundle\Entity\Alerte;
use StockBundle\Entity\Produit;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Validator\Constraints\Form;
use Symfony\Component\Form\Tests\Extension\Core\Type\SubmitTypeTest;
use Symfony\Component\Form\Tests\Extension\Validator\Type\SubmitTypeValidatorExtensionTest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="entrepot")
     */
    public function indexAction(Request $request)
    {




        $em = $this->getDoctrine()->getManager();
        $produit = $em->createQuery("SELECT A FROM StockBundle:Produit A WHERE A.quantiteStock <= A.quantiteSeuil
                                                                      AND  A.active LIKE '1' ")
            ->getResult();
        $alert=$this->getDoctrine()->getRepository('StockBundle:Alerte')->findAll();



        if ($produit) {

            $c = count($alert);
            $nb = count($produit);
            for ($i = 0; $i < $nb; $i++) {
                $t = 0;
              /*  for ($y = 0; $y < $c; $y++) {
                    if ($alert['0']->getProduit() == $produit[$i]) {
                        $t = 1;
                    }
                }*/



                    $a = new Alerte();

                    if (!$this->getDoctrine()->getRepository('StockBundle:Alerte')->findBy(array('produit' => $produit[$i]))) {


                        $a->setProduit($produit["$i"]);
                        $a->setDate(new \DateTime());
                        $a->setEtat('A Commander');
                        $em->persist($a);
                        $em->flush($a);


                    }
                }


                $this->get('session')->set('alert', '1');
                $this->get('session')->set('alertnb', "$nb");
                $this->addFlash('msg', 'Alerte !! Des Produits en etat critique ');
                $this->addFlash('type', 'alert-danger ');

            }
        else{
                $connection = $this->getDoctrine()->getConnection();
                $platform = $connection->getDatabasePlatform();

                $connection->executeUpdate($platform->getTruncateTableSQL('alerte', true));
                $this->get('session')->remove('alert');
                $this->get('session')->remove('alertnb');
                $this->addFlash('msg', 'Stock A jour ');
                $this->addFlash('type', 'alert-success ');
            }

        return $this->render("StockBundle::index.html.twig", array('active' => 'entrepot'));



    }


    /**
     * @Route("/alerte", name="alerte")
     */
    public function alerteAction(Request $request)
    {


        $em = $this->getDoctrine()->getManager();
        $session=$this->get('session');
        $session->remove('cmd');
        $session->remove('four');
        $session->remove('qte');

        $alertes = $this->getDoctrine()->getRepository('StockBundle:Alerte')->findBy(array(), array('date' => 'desc'));
       $form = $this->createFormBuilder()
            ->add('cherch', 'text', array('attr' => array(
                'class' => 'form-control',
                'required' => false,
                'placeholder'=>'Designation' ,
                'autocochmplete' => 'off')))
            ->add('type',ChoiceType::class,array(
                'choices'=>array('designation'=>'Designation',
                    'categorie'=>'Categorie',
                    'A Commander'=>'Non commander',
                    'Commandé'=>'Commandé',

                )
            ))
            ->add('chercher', 'submit')
            ->getForm();
        $this->get('session')->set('nbp',count($alertes));
        $this->addFlash('stock', "dd");
        $this->addFlash('type', 'alert-danger');

        $request = $this->get('request');
        $form->handleRequest($request);


        if ($form->isSubmitted()) {


            $data = $form->getData();
            $n = $data['cherch'];
            $type=$data['type'];



          if($type == 'A commander' || $type=='commander' ){

              $alertes = $this->getDoctrine()->getManager()
                  ->createQuery("SELECT A FROM StockBundle:Alerte A INNER JOIN A.produit p
                                     WHERE A.etat = :nom AND p.designation LIKE :des ")
                  ->setParameter('nom', $type)
                  ->setParameter('des', '%'.$n.'%')
                  ->getResult();


          }else {


              $alertes = $this->getDoctrine()->getManager()
                  ->createQuery("SELECT A FROM StockBundle:Alerte A INNER JOIN A.produit p
                                     WHERE p.designation LIKE :nom")
                  ->setParameter('nom', '%' . $data['cherch'] . '%')
                  ->getResult();
          }

            if (!$alertes) {
                $this->addFlash('msg', "Produits n'existe pas ");
                $this->addFlash('type', 'alert-danger');
            }
            $t = count($alertes);


            $this->addFlash('msg', "$t Produit(s) Trouvé(s) ");
            $this->addFlash('type', 'alert-info');
            return $this->render('StockBundle::alerte.html.twig', array('form' => $form->createView(),
                'active' => 'entrepot',
                'alertes' => $alertes,
            ));

        }
        return $this->render("StockBundle::alerte.html.twig", array('active' => 'entrepot'
        , 'alertes' => $alertes, 'form' => $form->createView()));


    }

    /**
     * @Route("/mouvement/{n}", name="mouvement")
     */
    public function mouvementAction($n)
    {

        $form = $this->createFormBuilder()
            ->add('date')
            ->add('submit','submit',array('label'=>'chercher'))
            ->getForm();

        $request = $this->get('request');
        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $data=$form->getData();
            $date=$data['date'];
            $produit= $this->getDoctrine()->getManager()
                ->createQuery("SELECT A FROM BackBundle:MouvementProduit A
                                     WHERE A.date LIKE :dt
                                     AND  A.statut LIKE :t ")
                ->setParameter('dt', "%".$date."%")
                ->setParameter('t', "%".$n."%")
                ->getResult();

            if($produit) {
                $c = count($produit);

                $this->addFlash('msg', "$c Produit(s) $n(s)");
                $this->addFlash('type', 'alert-success ');
            }
            else{
                $this->addFlash('msg', "Aucun produit $n ce jour");
                $this->addFlash('type', 'alert-info ');
            }
            return $this->render("StockBundle::mouvement.html.twig", array('type'=>$n,'list'=>$produit,'active' => 'entrepot'
            ,'form'=>$form->createView()));

        }

        $list=$this->getDoctrine()->getRepository('BackBundle:MouvementProduit')->findBy(array('statut'=>$n),

            array('date' => 'desc'));
        return $this->render("StockBundle::mouvement.html.twig", array('type'=>$n,'list'=>$list,'active' => 'entrepot'
                            ,'form'=>$form->createView()));


    }
}
