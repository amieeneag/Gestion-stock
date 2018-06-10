<?php

namespace BackBundle\Controller;

use StockBundle\Entity\Categorie;
use StockBundle\Entity\Marque;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StockBundle\Form\MarqueType;


class MarqueController extends Controller
{
    /**
     * Lists all marque entities.
     *
     * @Route("/marque", name="Amarque_index")
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $marques = $em->getRepository('StockBundle:Marque')->findAll();

        return $this->render('BackBundle::marque/index.html.twig', array(
            'marques' => $marques,'active'=>'entrepot'
        ));
    }

    /**
     * Finds and displays a marque entity.
     *
     * @Route("/marque/delete/{id}", name="Amarque_delete")
     *
     */
    public function deleteAction(Marque $marque)
    {



        $c = $this->getDoctrine()->getRepository('StockBundle:Marque')->find($marque);

        $em = $this->getDoctrine()->getEntityManager();


        $em->remove($c);
        $em->flush();
        $this->addFlash('msg','Marque supprimé');
        $this->addFlash('type','alert-success');


        return $this->redirect($this->generateUrl('Amarque_index'));
    }
    /**
     * Finds and displays a marque entity.
     *
     * @Route("/marque/modifier/{id}", name="Amarque_modifier")
     *
     */
    public function modifierAction(Marque $marque)
    {




        $request = $this->get('request');
        $form = $this->createForm(MarqueType::class , $marque);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($marque);
            $em->flush();
            $this->addFlash('msg','Marque Modifié ');
            $this->addFlash('type','alert-success');
            return $this->render('BackBundle::marque/show.html.twig', array('active' => 'entrepot',
                'marque' => $marque,
            ));


        }


        return $this->render('BackBundle::marque/modifier.html.twig', array('active' => 'entrepot',
            'form' => $form->createView()
        ));

    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/marque/{id}", name="Amarque_show")
     *
     */
    public function showAction(Marque $marque)
    {

        $produit = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($marque->getId());


        return $this->render('BackBundle::marque/show.html.twig', array('active' => 'entrepot',
            'marque' => $marque,
        ));
    }


    /**
     *
     *
     * @Route("/new/marque", name="Amarque_new")
     *
     */
    public function newAction()
    {

        $marque=new Marque();

        $request = $this->get('request');
        $form = $this->createForm(MarqueType::class,$marque);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();




            $em->persist($marque);
            $em->flush();
            $this->addFlash('msg','Marque ajouté avec succes');
            $this->addFlash('type','alert-success');
            return $this->render('BackBundle::marque/show.html.twig', array('active' => 'entrepot',
                'marque' => $marque
            ));


        }

        return $this->render('BackBundle::marque/new.html.twig', array('active'=>'entrepot',
            'form' => $form->createView()
        ));


    }
}
