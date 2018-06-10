<?php

namespace AchatBundle\Controller;
use AchatBundle\Entity\CommandeAchat;
use AchatBundle\Entity\LigneAchat;
use BackBundle\Entity\MouvementProduit;
use FactureBundle\Entity\FactureAchat;
use FactureBundle\Entity\FactureVente;
use StockBundle\Entity\Produit;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Validator\Constraints\DateTime;
use VenteBundle\Entity\CommandeVente;
use VenteBundle\Entity\LigneVente;
use FactureBundle\Form\FactureVenteType;
use StockBundle\Form\ProduitType;

class BonController extends Controller
{

    /**
     * @Route("/List/bon" ,name="listbon")
     */
    public function ListBonAction()
    {



        $form=$this->createFormBuilder()
            ->add('cherch','text',array('attr'=>array(
                    'class'=>'form-control',

                    'placeholder'=>'Entrez Num de commande' ,
                    'autocochmplete'=>'off'))

                 )



            ->add('chercher','submit')
            ->getForm();

        $request=$this->get('request');
        $form->handleRequest($request);


        if ($form->isSubmitted()) {
            $data = $form->getData();
            $id = $data['cherch'];
            $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->findBy(array('id'=>$id));
            return $this->render('AchatBundle::commande/list.html.twig',array('active'=>'achat','cmd'=>$cmd,'form'=>$form->createView()));



        }

        $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->findBy(array(),array('date'=>'desc'));

        return $this->render('AchatBundle::commande/list.html.twig',array('active'=>'achat','cmd'=>$cmd,'form'=>$form->createView()));



    }


    /**
     * @Route("/livraison/bon" ,name="bon")
     */
    public function newBonAction()
    {





        $form = $this->createFormBuilder()
            ->add('quantite','text',array('attr' => array(
                'class' => 'form-control',
                'autocomplete'=>false,
                'placeholder'=>'quantité' ,

            )))
            ->add('cherch', 'text', array('attr' => array(
                'class' => 'form-control',
                'autocomplete'=>false,
                'placeholder'=>'Designation ...' ,
            )))
            ->add('type',EntityType::class,array(
                'class'=>'StockBundle:Categorie'
            ))
            ->add('Ajouter', 'submit',array('attr'=>array('class'=>'btn btn-danger', )))
            ->getForm();
        $four=$this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->findAll();

        $this->addFlash('type', 'alert-danger');

        $request = $this->get('request');
        $form->handleRequest($request);




        return $this->render('AchatBundle::commande/newcmd.html.twig',array('four'=>$four,
            'active'=>'achat',
            'form'=>$form->createView()));




    }



    /**
     * @Route("/remplire/bon/{n}/{c}/{q}" ,name="Vbonnew")
     */
    public function bonAction($n,$c,$q)
    {

        $session=$this->get('session');

        if($session->has('bon')){
            $d=$session->get('bon');
        }else{
            $d=array();
        }


        $nom=$this->getDoctrine()->getRepository('StockBundle:Categorie')->createQueryBuilder('c')
            ->select('c')
            ->where("c.id = '$c' ")
            ->getQuery()->getResult();


        $cat='%'.$nom['0']->getCategorie().'%';

        $data = $this->getDoctrine()->getRepository('StockBundle:Produit')->createQueryBuilder('p')
            ->innerjoin("p.categorie", 'c')
            ->select('p.designation , p.id,p.prixAchat, p.quantiteStock')
            ->where("c.categorie LIKE '$cat' ")
            ->Andwhere("p.designation LIKE '$n' ")
            ->getQuery()->getResult();

        if($session->has('bon')){

            $d=$session->get('bon');

        }

        if($data) {



            $test=-1;
            for($i=0;$i<count($d);$i++){
                if($d[$i]['id']==$data['0']['id']){

                    $test=$i;
                }

            }


            if($test==-1) {

                $i = count($d);

                $d[$i]['designation'] = $data['0']['designation'];
                $d[$i]['prix'] = $data['0']['prixAchat'];
                $d[$i]['id'] = $data['0']['id'];
                $d[$i]['quantite'] = $q;
                $d[$i]['soustotal'] = $q * $data['0']['prixAchat'];

            }else{

                $d[$test]['quantite'] = $d[$test]['quantite']+$q;
                $d[$test]['soustotal'] =$d[$test]['soustotal']+$q*$d[$test]['prix'] ;

            }

            $session->set('bon', $d);
            $t=0;
            for($i=0;$i<count($d);$i++){
                $t=$t+$d[$i]['soustotal'];
            }
            $session->set('totalbon', $t);

        }else{
            return new Response('-1');
        }


        return new JsonResponse($d);


    }


    /**
     * @Route("/annuler/bon" ,name="annulerbon")
     */
    public function annulernewbonAction()
    {

        $d=array();$t=0;
        $this->get('session')->set('bon',$d);
        $this->get('session')->set('totalbon',$t);


        return $this->redirect($this->generateUrl('bon'));



    }


    /**
     * @Route("/supprimer/lignedebon/{id}" ,name="suplignebon")
     */
    public function suplignebonAction($id)
    {

        $d= $this->get('session')->get('bon');
        $t= $this->get('session')->get('totalbon');

        $data=array();
        $c=count($d);
        $y=0;

        for($i=0;$i<$c;$i++){

            if($d[$i]['id']!=$id){


                $data[$y]['designation'] = $d[$i]['designation'];
                $data[$y]['prix'] = $d[$i]['prix'];
                $data[$y]['id'] = $d[$i]['id'];
                $data[$y]['quantite'] =  $d[$i]['quantite'];
                $data[$y]['soustotal'] =  $d[$i]['soustotal'];
                $y=$y+1;

            }else{
                $t=$t - $d[$i]['soustotal'];
            }
        }


        $this->get('session')->set('bon',$data);
        $this->get('session')->set('totalbon',$t);

        return new Response($t);



    }


    /**
     * @Route("/voir/bon/{id}" ,name="Vbon_voir")
     */
    public function voirVbonAction($id)
    {

        $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->findOneBy(array('id'=>$id));

        $lg=$this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findBy(array('commande'=>$cmd));


        return $this->render('AchatBundle::commande/voir.html.twig',array('date'=>new \DateTime(),'active'=>'achat','cmd'=>$cmd,'lg'=>$lg));

    }

    /**
     * @Route("/valider/boncommande/{four}" ,name="validerbon")
     */
    public function VcmdcaliderAction($four)
    {

        $d= $this->get('session')->get('bon');
        $t= $this->get('session')->get('totalbon');
        $cmd=new CommandeAchat();
        $c=count($d);



        $user=$this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($this->get('session')->get('userId'));


        $four=$this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->find($four);
        $email=$four->getEmail();




        $em=$this->getDoctrine()->getManager();
        $cmd->setEtat('Passé');$cmd->setDate(new \DateTime());$cmd->setFournisseur($four);$cmd->setUtilisateur($user);$cmd->setTotal($t);

        $em->persist($cmd);
        $em->flush($cmd);


        $d= $this->get('session')->get('bon');
        $c=count($d);

        for($i=0;$i<$c;$i++){
            $lg=new LigneAchat();
            $lg->setProduit($this->getDoctrine()->getRepository('StockBundle:Produit')->find($d[$i]['id'])) ;
            $lg->setCommande($cmd);
            $lg->setPrixUnitaire($d[$i]['prix']);
            $lg->setSousTotal($d[$i]['soustotal']);
            $lg->setQuantite($d[$i]['quantite']);
            $em->persist($lg);
            $em->flush($lg);

        }
        $lg=$this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findBy(array('commande'=>$cmd));

        $msg=(new \Swift_Message('Nouvelle Commande'))
            ->setFrom("amieene.ag@gmail.com")
            ->setTo("$email")
            ->setBody( $this->renderView( 'AchatBundle::cmd.html.twig',array('four'=>$four,'lg'=>$lg,'cmd'=>$cmd)),

                'text/html');

        $x=$this->get('mailer')->send($msg);

        $d=array();$t=0;
        $this->get('session')->set('bon',$d);
        $this->get('session')->set('totalbon',$t);

        return $this->redirect($this->generateUrl('Vbon_voir',array('id'=>$cmd->getId())));

    }

    /**
     * @Route("/annuler/bon/{id}" ,name="Vbon_annuler")
     */
    public function annulerbonAction($id)
    {
           $c=new CommandeAchat();
            $c=$this->getDoctrine()->getRepository('AchatBundle:Achat')->find($id);
            $c->setEtat('Annuler');
        $this->getDoctrine()->getManager()->persist($c);
        $this->getDoctrine()->getManager()->flush($c);
        return $this->redirect($this->generateUrl('achat'));

    }

    /**
     * @Route("/regler/bon/{id}" ,name="Vbon_facture")
     */
    public function reglerbonAction($id)
    {


        $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->find($id);
        $lg=$this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findby(array('commande'=>$cmd));
        $user=$this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($this->get('session')->get('userId'));

        $em=$this->getDoctrine()->getManager();


        $f=new FactureAchat();



            $f->setTotal($cmd->getTotal());$f->setUtilisateur($user);$f->setDate(new \DateTime());
            $f->setCommandeAchat($cmd);
            $em->persist($f);
            $em->flush($f);
            for($i=0;$i<count($lg);$i++) {
                $m = new MouvementProduit();
                $m->SetDate(new \DateTime());
                $m->SetQuantite($lg[$i]->getQuantite());
                $m->SetStatut("sorti");
                $m->setProduit($lg[$i]->getProduit());
                $em->persist($m);
                $em->flush($m);
                $prod = new Produit();
                $prod = $lg[$i]->getProduit();
                $prod->SetQuantiteStock($prod->getQuantiteStock() + $lg[$i]->getQuantite());
                $em->persist($prod);
                $em->flush($prod);


                return $this->redirect($this->generateUrl('Lfacture_voir', array('id' => $f->getId())));


            }
    }




}


