<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class FactureController extends Controller
{
    /**
     * @Route("/vente/facture/{id}" ,name="facture_voir")
     */
    public function reglerAction($id)
    {

        $f=$this->getDoctrine()->getRepository('FactureBundle:FactureVente')->find($id);
        $lg=$this->getDoctrine()->getRepository('VenteBundle:LigneVente')->findBy(array('commande'=>$f->getCommandeVente()));

        $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->find($f->getCommandeVente()->getId());
        $lg=$this->getDoctrine()->getRepository('VenteBundle:LigneVente')->findBy(array('commande'=>$cmd));
        $c=count($lg);

        $v=0;$t=0;
        for($i=0;$i<$c;$i++){
            $v=$v+$lg[$i]->getTva();
            $t=$t+$lg[$i]->getSousTotal();
        }


        return $this->render('FactureBundle::voir.html.twig',array('v'=>$v,'t'=>$t,'active'=>'vente','lg'=>$lg,'fact'=>$f));
    }




    /**
     * @Route("/facture/List/" ,name="facture_list")
     */
    public function ListVenteAction()
    {

            $form=$this->createFormBuilder()
                    ->add('cherch','text',array('attr'=>array('placeholder'=>'Entrer Num Facture')))
                    ->add('chercher','submit')
                ->getForm();
        $request=$this->get('request');
        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $num=$form->getData()['cherch'];

            $fact=$this->getDoctrine()->getRepository('FactureBundle:FactureVente')
                ->findBy(array('id'=>$num),array('date'=>'desc'));
            if($num==""){
                $fact=$this->getDoctrine()->getRepository('FactureBundle:FactureVente')->findBy(array(),array('date'=>'desc'));
                return $this->render('FactureBundle::list.html.twig',array('form'=>$form->createView(), 'active'=>'vente','fact'=>$fact));

            }
            if($fact){
                return $this->render('FactureBundle::list.html.twig',array('form'=>$form->createView(), 'active'=>'vente','fact'=>$fact));

            }
            else{
                $this->addFlash('msg',"Aucune facture trouvée");
                $this->addFlash('type','alert-danger');
                return $this->render('FactureBundle::list.html.twig',array('form'=>$form->createView(), 'active'=>'vente','fact'=>$fact));


            }
        }

            $fact=$this->getDoctrine()->getRepository('FactureBundle:FactureVente')->findBy(array(),array('date'=>'desc'));
            return $this->render('FactureBundle::list.html.twig',array('form'=>$form->createView(), 'active'=>'vente','fact'=>$fact));





    }

}
