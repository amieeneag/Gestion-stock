<?php

namespace BackBundle\Controller;

use AchatBundle\Entity\Fournisseur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BackBundle\Form\FournisseurType;

/**
 *  controller.
 *
 * @Route("fournisseur")
 */
class FournisseurController extends Controller
{
    /**
     * Lists all fournisseur entities.
     *
     * @Route("/", name="Afournisseur_index")
     *
     */
    public function indexAction()
    {

        $form = $this->createFormBuilder()
            ->add('cherch', 'text', array('attr' => array(
                'class' => 'form-control ',
                'placeholder' => 'Nom/Prenom/statut/Active...',
                'autocochmplete' => 'off')))
            ->add('chercher', 'submit')
            ->getForm();
        $request = $this->get('request');
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {


            $data = $form->getData();
            if ($data['cherch'] == 'yes') {
                $data['cherch'] = 1;
            }
            if ($data['cherch'] == 'no') {
                $data['cherch'] = 0;

            }
            $fournisseurs = $this->getDoctrine()->getManager()->createQuery("SELECT A FROM AchatBundle:Fournisseur A WHERE A.nom LIKE :nom OR
                                                                       A.prenom LIKE :prenom OR
                                                                         A.raisonSocial LIKE :statut OR
                                                                       A.active LIKE :active ")

                ->setParameter('statut', '%' . $data['cherch'] . '%')
                ->setParameter('nom', '%' . $data['cherch'] . '%')
                ->setParameter('active', '%' . $data['cherch'] . '%')
                ->setParameter('prenom', '%' . $data['cherch'] . '%')->getResult();

            if (!$fournisseurs) {
                $this->addFlash('msg', "Fournisseur n'existe pas ");
                $this->addFlash('type', 'alert-danger');
            }
            $t = count($fournisseurs);


            $this->addFlash('msg', "$t Fournisseur Trouvé(s) ");
            $this->addFlash('type', 'alert-info');
            return $this->render('BackBundle::fournisseur/index.html.twig', array('form' => $form->createView(),
                'active' => 'achat',
                'fournisseurs' => $fournisseurs,
            ));

        }


        $fournisseurs = $em->getRepository('AchatBundle:Fournisseur')->findAll();

        return $this->render('BackBundle::fournisseur/index.html.twig', array(
            'fournisseurs' => $fournisseurs, 'active' => 'achat', 'form' => $form->createView()
        ));


    }

    /**
     * Finds and displays a fournisseur entity.
     *
     * @Route("/{id}", name="Afournisseur_show")
     *
     */
    public function showAction(Fournisseur $fournisseur)
    {


        return $this->render('BackBundle::fournisseur/show.html.twig', array(
            'fournisseur' => $fournisseur,'active'=>'achat'
        ));
    }

    /**
     * Finds and displays a fournisseur entity.
     *
     * @Route("/modifier/{id}", name="Afournisseur_modifier")
     *
     */
    public function modifierAction(Fournisseur $fournisseur)
    {


        $request = $this->get('request');
        $form = $this->createForm(FournisseurType::class, $fournisseur);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {


            $em = $this->getDoctrine()->getManager();


            $em->persist($fournisseur);
            $em->flush();
            $this->addFlash('msg', 'fournisseur Modifié ');
            $this->addFlash('type', 'alert-success');
            return $this->render('BackBundle::fournisseur/show.html.twig', array('active' => 'achat',
                'fournisseur' => $fournisseur,
            ));


        }


        return $this->render('BackBundle::fournisseur/modifier.html.twig', array('active' => 'achat',
            'form' => $form->createView()
        ));
    }


    /**
     * Finds and displays a fournisseurentity.
     *
     * @Route("/new/fournisseur", name="Afournisseur_new")
     *
     */
    public function newAction()
    {

        $fournisseur=new fournisseur();

        $request = $this->get('request');
        $form = $this->createForm(fournisseurType::class,$fournisseur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $em = $this->getDoctrine()->getManager();

            $em->persist($fournisseur);
            $em->flush();

            $this->addFlash('msg', 'Fournisseur ajouté avec succes');
            $this->addFlash('type', 'alert-success');
            return $this->render('BackBundle::fournisseur/show.html.twig', array('active' => 'achat',
                'fournisseur' => $fournisseur
            ));


        }

        return $this->render('BackBundle::fournisseur/new.html.twig', array('active' => 'achat',
            'form' => $form->createView()
        ));


    }
    /**
     * Finds and displays a fournisseur entity.
     *
     * @Route("/delete/{id}", name="Afournisseur_delete")
     *
     */
    public function deleteAction(Fournisseur $fournisseur)
    {


        $p = $this->getDoctrine()->getRepository('AchatBundle:fournisseur')->find($fournisseur);
        $em = $this->getDoctrine()->getEntityManager();


        $em->remove($p);
        $em->flush();
        $this->addFlash('msg','Fournisseur supprimé');
        $this->addFlash('type','alert-success');


        return $this->redirect($this->generateUrl('Afournisseur_index'));

    }

}
