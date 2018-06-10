<?php

namespace BackBundle\Controller;

use StockBundle\Entity\Categorie;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StockBundle\Form\CategorieType;


class CategorieController extends Controller
{
    /**
     * Lists all categorie entities.
     *
     * @Route("/categorie", name="Acategorie_index")
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('StockBundle:Categorie')->findAll();

        return $this->render('BackBundle::categorie/index.html.twig', array(
            'categories' => $categories,'active'=>'entrepot'
        ));
    }

    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/categorie/delete/{id}", name="Acategorie_delete")
     *
     */
    public function deleteAction(Categorie $categorie)
    {



        $c = $this->getDoctrine()->getRepository('StockBundle:Categorie')->find($categorie->getId());
        $em = $this->getDoctrine()->getEntityManager();


        $em->remove($c);
        $em->flush();
        $this->addFlash('msg','Categorie supprimé');
        $this->addFlash('type','alert-success');


        return $this->redirect($this->generateUrl('Acategorie_index'));
    }
    /**
     * Finds and displays a categorie entity.
     *
     * @Route("/categorie/modifier/{id}", name="Acategorie_modifier")
     *
     */
    public function modifierAction(Categorie $categorie)
    {




        $request = $this->get('request');
        $form = $this->createForm(CategorieType::class , $categorie);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $em->persist($categorie);
            $em->flush();
            $this->addFlash('msg','Categorie Modifié ');
            $this->addFlash('type','alert-success');
            return $this->render('BackBundle::categorie/show.html.twig', array('active' => 'entrepot',
                'categorie' => $categorie,
            ));


        }


        return $this->render('BackBundle::categorie/modifier.html.twig', array('active' => 'entrepot',
            'form' => $form->createView()
        ));

    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/categorie/{id}", name="Acategorie_show")
     *
     */
    public function showAction(Categorie $categorie)
    {

        $produit = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($categorie->getId());


        return $this->render('BackBundle::categorie/show.html.twig', array('active' => 'entrepot',
            'categorie' => $categorie,
        ));
    }


    /**
     *
     *
     * @Route("/new/categorie", name="Acategorie_new")
     *
     */
    public function newAction()
    {

        $categorie=new Categorie();

        $request = $this->get('request');
        $form = $this->createForm(CategorieType::class,$categorie);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();




            $em->persist($categorie);
            $em->flush();
            $this->addFlash('msg','Categorie ajouté avec succes');
            $this->addFlash('type','alert-success');
            return $this->render('BackBundle::categorie/show.html.twig', array('active' => 'entrepot',
                'categorie' => $categorie
            ));


        }

        return $this->render('BackBundle::categorie/new.html.twig', array('active'=>'entrepot',
            'form' => $form->createView()
        ));


    }
}
