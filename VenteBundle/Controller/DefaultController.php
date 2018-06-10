<?php

namespace VenteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Encoder\JsonEncode;

class DefaultController extends Controller
{
    /**
     * @Route("/" ,name="vente")
     */
    public function indexAction()
    {
        return $this->render('VenteBundle::index.html.twig',array('active'=>'vente'));
    }

    /**
     * @Route("/new/commande/complete/{n}/{c}" ,name="complete")
     */
    public function completeAction($n,$c)
    {


        $nom=$this->getDoctrine()->getRepository('StockBundle:Categorie')->createQueryBuilder('c')
            ->select('c')
            ->where("c.id = '$c' ")
            ->getQuery()->getResult();



        $cat='%'.$nom['0']->getCategorie().'%';
        $des='%'.$n.'%';
        $data = $this->getDoctrine()->getRepository('StockBundle:Produit')->createQueryBuilder('p')
            ->innerjoin("p.categorie", 'c')
            ->select('p.designation')
            ->where("c.categorie LIKE '$cat' ")
            ->Andwhere("p.designation LIKE '$des' ")
            ->getQuery()->getResult();


            for($i=0;$i<count($data);$i++){
                $d[]=$data[$i]['designation'];
            }


        return new JsonResponse($d);

    }

    /**
     * @Route("/client/complete/{n}" ,name="clientcomplete")
     */
    public function clientcompleteAction($n)
    {


        $des='%'.$n.'%';
        $data = $this->getDoctrine()->getRepository('VenteBundle:Client')->createQueryBuilder('c')
            ->select('c.nom,c.prenom,c.id')
            ->where("c.nom LIKE '$des' ")
            ->Orwhere("c.prenom LIKE '$des' ")
            ->getQuery()->getResult();


        for($i=0;$i<count($data);$i++){
            $d[]=$data[$i]['nom'].' '.$data[$i]['prenom'];

        }

            return new JsonResponse($d);

    }

    /**
     * @Route("/client/valider/{n}" ,name="clientvalider")
     */
    public function clientvaliderAction($n)
    {



        $x="ZZZZ";

        $c = explode ( ' ', $n);

        if(count($c)==1){ return new Response("non");}

        $data = $this->getDoctrine()->getRepository('VenteBundle:Client')->createQueryBuilder('c')
            ->select('c.nom,c.prenom,c.id')
            ->where("c.nom LIKE '$c[0]' ")
            ->Andwhere("c.prenom LIKE '$c[1]' ")
            ->getQuery()->getResult();


            if($data){

                $this->get('session')->set('clientId',$data[0]['id']);
                return new Response("oui");
            }
        return new JsonResponse("non");

    }





}
