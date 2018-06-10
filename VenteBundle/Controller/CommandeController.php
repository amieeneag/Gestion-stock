<?php

namespace VenteBundle\Controller;

use BackBundle\Entity\MouvementProduit;
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

class CommandeController extends Controller
{
    /**
     * @Route("/new/commande/" ,name="Vcmd_new")
     */
    public function indexAction()
    {

        $session=$this->get('session');

             $form = $this->createFormBuilder()
            ->add('quantite','text',array('attr' => array(
                     'class' => 'form-control',
                     'autocomplete'=>false,
                'placeholder'=>'Qte ' ,

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


        $this->addFlash('type', 'alert-danger');

        $request = $this->get('request');
        $form->handleRequest($request);




            return $this->render('VenteBundle::commande/newcmd.html.twig',array('active'=>'vente',
                 'form'=>$form->createView()));
    }


    /**
     * @Route("/remplire/commande/{n}/{c}/{q}" ,name="Vcmdnew")
     */
    public function cmdnewAction($n,$c,$q)
    {

        $session=$this->get('session');

        if($session->has('commande')){
            $d=$session->get('commande');
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
            ->select('p.designation ,p.tva, p.id,p.prixVente, p.quantiteStock')
            ->where("c.categorie LIKE '$cat' ")
            ->Andwhere("p.designation LIKE '$n' ")
            ->getQuery()->getResult();

        if($session->has('commande')){

            $d=$session->get('commande');

            }

        if($data) {

            if($data['0']['quantiteStock']<$q){
                return new Response('-2');
            }

            $test=-1;
            for($i=0;$i<count($d);$i++){
                if($d[$i]['id']==$data['0']['id']){
                  $prod=$data['0']['quantiteStock']-$d[$i]['quantite']-$q;
                    if($prod<0){return new Response('-2'); }
                    $test=$i;
                }

            }


            if($test==-1) {

                $i = count($d);

                $d[$i]['designation'] = $data['0']['designation'];
                $d[$i]['prix'] = $data['0']['prixVente'];
                $d[$i]['id'] = $data['0']['id'];
                $d[$i]['quantite'] = $q;
                $d[$i]['tauxtva']=$data['0']['tva'];
                $d[$i]['tva']=$data['0']['tva']*$q*$data['0']['prixVente']/100;
                $d[$i]['soustotal'] = $q * $data['0']['prixVente'];

            }else{

                $d[$test]['quantite'] = $d[$test]['quantite']+$q;
                $d[$test]['soustotal'] =$d[$test]['soustotal']+$q*$d[$test]['prix'] ;
                $d[$test]['tva'] = $d[$test]['tva']+$q*$d[$test]['prix']* $d[$test]['tauxtva']/100;

            }



            $session->set('commande', $d);
            $t=0;
            $v=0;
           for($i=0;$i<count($d);$i++){
               $t=$t+$d[$i]['soustotal'];
               $v=$v+$d[$i]['tva'];
           }
            $session->set('total', $t);
            $session->set('tva', $v);
            $session->set('totalttc', $v+$t);

        }else{
            return new Response('-1');
        }


        return new JsonResponse($d);


    }


    /**
     * @Route("/annuler/commande" ,name="annulercmd")
     */
    public function annulercmdAction()
    {
        $d=array();$t=0;
        $this->get('session')->set('commande',$d);
        $this->get('session')->set('total',$t);

       return $this->redirect($this->generateUrl('Vcmd_new'));



    }

    /**
     * @Route("/commande/annuler/{id}" ,name="annulercommande")
     */
    public function annulerAction($id)
    {


        $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->find($id);
        $cmd->setEtat('Annulé');
        $em=$this->getDoctrine()->getManager();
        $em->persist($cmd);
        $em->flush($cmd);

        return $this->redirect($this->generateUrl('Vcmd_new'));



    }



    /**
     * @Route("/supprimer/lignecommande/{id}" ,name="suplignecmd")
     */
    public function suplignecmdAction($id)
    {

            $d= $this->get('session')->get('commande');
            $t= $this->get('session')->get('total');
           $v= $this->get('session')->get('tva');

            $data=array();
            $c=count($d);
        $y=0;

        for($i=0;$i<$c;$i++){

             if($d[$i]['id']!=$id){


                $data[$y]['designation'] = $d[$i]['designation'];
                $data[$y]['prix'] = $d[$i]['prix'];
                $data[$y]['id'] =  $d[$i]['id'];
                 $data[$y]['tauxtva'] =  $d[$i]['tauxtva'];
                 $data[$y]['tva'] =  $d[$i]['tva'];
                $data[$y]['quantite'] =  $d[$i]['quantite'];
                $data[$y]['soustotal'] =  $d[$i]['soustotal'];
                 $y=$y+1;

            }else{
                 $t=$t - $d[$i]['soustotal'];
                 $v=$v - $d[$i]['tva'];
             }
        }


        $this->get('session')->set('commande',$data);
        $this->get('session')->set('total',$t);
        $this->get('session')->set('tva',$v);
        $this->get('session')->set('totalttc',$t+$v);
        return new Response($t+$v);



    }

    /**
     * @Route("/voir/commande/{id}" ,name="Vcmd_voir")
     */
    public function voirVcmdAction($id)
    {

            $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->findOneBy(array('id'=>$id));
            $lg=$this->getDoctrine()->getRepository('VenteBundle:LigneVente')->findBy(array('commande'=>$cmd));
        $c=count($lg);

        $v=0;$t=0;
        for($i=0;$i<$c;$i++){
            $v=$v+$lg[$i]->getTva();
            $t=$t+$lg[$i]->getSousTotal();
        }
        return $this->render('VenteBundle::commande/voir.html.twig',array('date'=>new \DateTime(), 't'=>$t,'v'=>$v,'active'=>'vente','cmd'=>$cmd,'lg'=>$lg));

    }

    /**
     * @Route("/valider/commande" ,name="Vcmd_valider")
     */
    public function VcmdcaliderAction()
    {

        $d= $this->get('session')->get('commande');
        $t= $this->get('session')->get('totalttc');
        $cmd=new CommandeVente();
        $c=count($d);


        $user=$this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($this->get('session')->get('userId'));


        $client=$this->getDoctrine()->getRepository('VenteBundle:Client')->find($this->get('session')->get('clientId'));

        $em=$this->getDoctrine()->getManager();
        $cmd->setEtat('Passé');$cmd->setDate(new \DateTime());$cmd->setClient($client);$cmd->setUtilisateur($user);
        $cmd->setTotal($t);
        $em->persist($cmd);
        $em->flush($cmd);

        $d= $this->get('session')->get('commande');
        $c=count($d);

        for($i=0;$i<$c;$i++){
            $lg=new LigneVente();
             $lg->setProduit($this->getDoctrine()->getRepository('StockBundle:Produit')->find($d[$i]['id'])) ;
            $lg->setCommande($cmd);
            $lg->setTva($d[$i]['tva']);
            $lg->setPrixUnitaire($d[$i]['prix']);
            $lg->setSousTotal($d[$i]['soustotal']);
            $lg->setQuantite($d[$i]['quantite']);
            $em->persist($lg);
            $em->flush($lg);

                   }

        $d=array();$t=0;
        $this->get('session')->set('commande',$d);
        $this->get('session')->set('total',$t);
        $this->get('session')->set('totalttc',$t);
        $this->get('session')->set('tva',$t);



        return $this->redirect($this->generateUrl('Vcmd_voir',array('id'=>$cmd->getId())));

    }


    /**
     * @Route("/list/commandeVente" ,name="Vcmd_list")
     */
    public function VcmdlistAction()
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
            if($id==""){
                $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->findBy(array(),array('date'=>'desc'));

                return $this->render('VenteBundle::commande/list.html.twig',array('active'=>'vente','cmd'=>$cmd,'form'=>$form->createView()));

            }
            $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->findBy(array('id'=>$id));
            return $this->render('VenteBundle::commande/list.html.twig',array('active'=>'vente','cmd'=>$cmd,'form'=>$form->createView()));



        }

        $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->findBy(array(),array('date'=>'desc'));

        return $this->render('VenteBundle::commande/list.html.twig',array('active'=>'vente','cmd'=>$cmd,'form'=>$form->createView()));


    }


    /**
     * @Route("/regler/{id}" ,name="Vcmd_regler")
     */
    public function reglerAction($id)
    {


        $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->find($id);
        $lg=$this->getDoctrine()->getRepository('VenteBundle:LigneVente')->findby(array('commande'=>$cmd));
        $user=$this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($this->get('session')->get('userId'));

        $form=$this->createFormBuilder()
            ->add('remise','number',array('label'=>'Remise(%)'))

            ->add('paiement',ChoiceType::class,array('choices'=>
                array('espece'=>'Espece','cheque'=>'Cheque')
            ))

            ->add('total','number',array('label'=>"Total TTC (DH)"))
            ->add('Regler','submit',array('attr'=>array('class'=>'btn btn-danger')))
            ->getForm();

        $request = $this->get('request');


        $form->handleRequest($request);

        $em=$this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {
            $em=$this->getDoctrine()->getManager();
            $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->find($id);
            $data = $form->getData();
            $f=new FactureVente();
            $cmd->setEtat('Reglé');
            $em->persist($cmd);
            $em->flush($cmd);

            $f->setRemise($data['remise']);
            $f->setTotal($cmd->getTotal()-$cmd->getTotal()*$data['remise']/100);$f->setUtilisateur($user);$f->setDate(new \DateTime());
            $f->setCommandeVente($cmd);$f->setModePaiement($data['paiement']);
            $em->persist($f);
            $em->flush($f);
            for($i=0;$i<count($lg);$i++){
                $m=new MouvementProduit();
                $m->SetDate(new \DateTime());$m->SetQuantite($lg[$i]->getQuantite());
                $m->SetStatut("sorti");
                $m->setProduit($lg[$i]->getProduit());
                $em->persist($m);
                $em->flush($m);
                $prod=new Produit();
                $prod=$lg[$i]->getProduit();
                $prod->SetQuantiteStock($prod->getQuantiteStock()-$lg[$i]->getQuantite());
                $em->persist($prod);
                $em->flush($prod);


            }





            return $this->redirect($this->generateUrl('facture_voir',array('id'=>$f->getId())));

        }



        $date=new \DateTime();



        return $this->render('VenteBundle::commande/regler.html.twig',array('active'=>'vente','cmd'=>$cmd,'lg'=>$lg,
                                'user'=>$user,'date'=>$date ,'form'=>$form->createView()));
    }






}
