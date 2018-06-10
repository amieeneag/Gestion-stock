<?php

namespace AchatBundle\Controller;
use AchatBundle\Entity\CommandeAchat;
use AchatBundle\Entity\LigneAchat;
use BackBundle\Entity\MouvementProduit;
use FactureBundle\Entity\FactureVente;
use StockBundle\Entity\Alerte;
use StockBundle\Entity\Categorie;
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

class DefaultController extends Controller
{
    /**
     * @Route("/" ,name="achat")
     */
    public function indexAction()
    {
        return $this->render('AchatBundle::index.html.twig',array('active'=>'achat'));

    }

    /**
     * @Route("/chercher/fournisseur/{id}" ,name="chercherfournisseur")
     */
    public function fourAction($id)
    {

        $cat=$this->getDoctrine()->getRepository('StockBundle:Alerte')->find($id);

        $cat=$cat->getProduit()->getCategorie() ;

        $four =  $this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->findBy(array());
        $c=count($four);

        $f=array();
        $i=0;

        $y=0;
        for($i=0;$i<$c;$i++){
            $x=new Categorie();
            $x=$four[$i]->getCategories()->getValues();
              if(in_array($cat,$x)){
                  $f[$y]['id']=$four[$i]->getId();
                  $f[$y]['nom']=$four[$i]->getNom();
                  $f[$y]['prenom']=$four[$i]->getPrenom();
                  $y++;
              }


        }




        return new JsonResponse($f);


    }


    /**
     * @Route("/remplir/cmd/{four}/{a}/{q}" ,name="listfournisseur")
     */
    public function cmdAction($four,$a,$q)
    {
        $session=$this->get('session');


        $p=$this->getDoctrine()->getRepository('StockBundle:Alerte')->find($a);
        $produit=$p->getProduit()->getId();
        $f= $p=$this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->find($four);
        if($session->has('cmd')){ $cmd=$session->get('cmd');}
        else{$cmd=array();}
        if($session->has('four')){$four=$session->get('four');}
        else{  $four=array(); }
        if($session->has('qte')){$qte=$session->get('qte');}
        else{  $qte=array(); }


        $cmd[$f->getId()][]=$produit;
        $c=count($cmd[$f->getId()]);
        $qte[$f->getId()][$c-1]=$q;
        if(!in_array($f->getId(),$four)){
            $four[]=$f->getId();
        }
        $session->set('cmd',$cmd);
        $session->set('four',$four);
        $session->set('qte',$qte);


        return new Response('1');


    }
    /**
     * @Route("/valider/cmd/" ,name="validercmd")
     */
    public function validercmdAction()
    {

        $session = $this->get('session');
        $em = $this->getDoctrine()->getManager();
        $c = $session->get('cmd');
        $qte = $session->get('qte');
        $four = $session->get('four');
        $f = count($four);


        for ($i = 0; $i < $f; $i++) {
            $x=$four[$i];
            $t = 0;

            for ($y = 0; $y < count($c[$x]); $y++) {
                $t = $t + $this->getDoctrine()->getRepository('StockBundle:Produit')->find($c[$x][$y])->getPrixVente()
                *$qte[$x][$y];

            }


            $cmd = new CommandeAchat();
            $user = $this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($this->get('session')->get('userId'));
            $cmd->setEtat('Passé');
            $cmd->setDate(new \DateTime());
            $cmd->setFournisseur($this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->find($four[$i]));
            $cmd->setUtilisateur($user);
            $cmd->setTotal($t);
            $em->persist($cmd);
            $em->flush($cmd);

            for ($y = 0; $y < count($c[$four[$i]]); $y++) {

                $q=$qte[$four[$i]][$y];
                $lg = new LigneAchat();

                $p=$this->getDoctrine()->getRepository('StockBundle:Produit')->find($c[$four[$i]][$y]);
                $a=$this->getDoctrine()->getRepository('StockBundle:Alerte')->findBy(array('produit'=>$p));

                $a['0']->setEtat('Commandé');
                $em->persist($a['0']);
                $em->flush($a['0']);
                $lg->setProduit($p);
                $lg->setCommande($cmd);
                $lg->setPrixUnitaire($p->getPrixAchat());
                $lg->setSousTotal($p->getPrixAchat() * $q);
                $lg->setQuantite($q);
                $em->persist($lg);
                $em->flush($lg);

            }
            $lg=$this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findBy(array('commande'=>$cmd));
            $email=$this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->find($four[$i])->getEmail();
            $msg=(new \Swift_Message('Nouvelle Commande'))
                ->setFrom("amieene.ag@gmail.com")
                ->setTo("$email")
                ->setBody( $this->renderView( 'AchatBundle::cmd.html.twig',array('four'=>$this->getDoctrine()->getRepository('AchatBundle:Fournisseur')->find($four[$i])
                ,'lg'=>$lg,'cmd'=>$cmd)),

                    'text/html');

            $x=$this->get('mailer')->send($msg);


        }
        $session->remove('cmd');
        $session->remove('four');


        return $this->redirect($this->generateUrl('alerte'));



    }

    /**
     * @Route("/valider/annul/gener/cmd" ,name="annulcmdgener")
     */
    public function annulcmdAction()
    {
        $session=$this->get('session');
        $session->remove('cmd');
        $session->remove('four');
        $session->remove('qte');

        return $this->redirect($this->generateUrl('alerte'));
    }
    /**
     * @Route("/livraison/cmd/{id}" ,name="livraisoncmd")
     */
    public function livcmdAction($id)
    {

        $form=$this->createFormBuilder()
            ->add('cherch','text',array('attr'=>array(
                    'class'=>'form-control',

                    'placeholder'=>'Num de commande' ,
                    'autocochmplete'=>'off'))

            )



            ->add('chercher','submit')
            ->getForm();

        $request=$this->get('request');
        $form->handleRequest($request);

        $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->find($id);
        if($cmd){
            $lg=$this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findBy(array('commande'=>$cmd));

            return $this->render('AchatBundle::livraison.html.twig', array('date' => new \DateTime(), 'active' => 'achat', 'lg' => $lg, 'cmd' => $cmd, 'form' => $form->createView()));

        }

        if ($form->isSubmitted()) {
            $data = $form->getData();
            $id = $data['cherch'];
            $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->find($id);

            $lg=$this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findBy(array('commande'=>$cmd));
            if($cmd) {
                return $this->render('AchatBundle::livraison.html.twig', array('date' => new \DateTime(), 'active' => 'achat', 'lg' => $lg, 'cmd' => $cmd, 'form' => $form->createView()));
            }else{

                $this->addFlash('msg'," acune commande trouvée avec ce numero ");
                $this->addFlash('type','alert-danger');
                return $this->render('AchatBundle::livraison.html.twig',array('active'=>'achat','form'=>$form->createView()));


            }


        }

        return $this->render('AchatBundle::livraison.html.twig',array('active'=>'achat','form'=>$form->createView()));
    }

    /**
     * @Route("/livrer/end/cmd/{id}" ,name="livrecmdend")
     */
    public function livrecmdendAction($id)
    {


            $cmd = $this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->find($id);
            $lg = $this->getDoctrine()->getRepository('AchatBundle:LigneAchat')->findBy(array('commande' => $cmd));
            $cmd->setEtat('Livré');
            $em = $this->getDoctrine()->getManager();

            $em->persist($cmd);
            $em->flush($cmd);
            for ($i = 0; $i < count($lg); $i++) {

                $p = $lg[$i]->getProduit();
                $produit = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($p->getID());
                $alert = $this->getDoctrine()->getRepository('StockBundle:Alerte')->findBy(array('produit' => $produit));

                $produit->setQuantiteStock($produit->getQuantiteStock() + $lg[$i]->getQuantite());
                if ($alert) {
                    $em->remove($alert['0']);
                }
                $m = new MouvementProduit();
                $m->SetDate(new \DateTime());
                $m->SetQuantite($lg[$i]->getQuantite());
                $m->SetStatut("entré");
                $m->setProduit($lg[$i]->getProduit());
                $em->persist($m);
                $em->flush($m);

                $em->persist($produit);
                $em->flush($produit);
                $this->addFlash('msg',"Stock s'est mis à jour");$this->addFlash('type','alert-success');
                return $this->redirect($this->generateUrl('livraisoncmd',array('id'=>$cmd->getId())));
            }


        return $this->redirect($this->generateUrl('livraisoncmd',array('id'=>$cmd->getId())));



    }


}
