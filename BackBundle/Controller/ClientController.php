<?php

namespace BackBundle\Controller;

use VenteBundle\Entity\Client;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BackBundle\Form\ClientType;

/**
 * Client controller.
 *
 * @Route("client")
 */
class ClientController extends Controller
{
    /**
     * Lists all client entities.
     *
     * @Route("/", name="Aclient_index")
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
            $clients = $this->getDoctrine()->getManager()->createQuery("SELECT A FROM VenteBundle:Client A WHERE A.nom LIKE :nom OR
                                                                       A.prenom LIKE :prenom OR
                                                                         A.statut LIKE :statut OR
                                                                       A.active LIKE :active ")

                ->setParameter('statut', '%' . $data['cherch'] . '%')
                ->setParameter('nom', '%' . $data['cherch'] . '%')
                ->setParameter('active', '%' . $data['cherch'] . '%')
                ->setParameter('prenom', '%' . $data['cherch'] . '%')->getResult();

            if (!$clients) {
                $this->addFlash('msg', "Client n'existe pas ");
                $this->addFlash('type', 'alert-danger');
            }
            $t = count($clients);


            $this->addFlash('msg', "$t Client Trouvé(s) ");
            $this->addFlash('type', 'alert-info');
            return $this->render('BackBundle::client/index.html.twig', array('form' => $form->createView(),
                'active' => 'vente',
                'clients' => $clients,
            ));

        }


        $clients = $em->getRepository('VenteBundle:Client')->findAll();

        return $this->render('BackBundle::client/index.html.twig', array(
            'clients' => $clients, 'active' => 'vente', 'form' => $form->createView()
        ));


    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/{id}", name="Aclient_show")
     *
     */
    public function showAction(Client $client)
    {

        return $this->render('BackBundle::client/show.html.twig', array(
            'client' => $client,'active'=>'vente'
        ));
    }

    /**
     * Finds and displays a client entity.
     *
     * @Route("/modifier/{id}", name="Aclient_modifier")
     *
     */
    public function modifierAction(Client $client)
    {


        $request = $this->get('request');
        $form = $this->createForm(ClientType::class, $client);

        $form->handleRequest($request);


        if ($form->isSubmitted()) {


            $em = $this->getDoctrine()->getManager();


            $em->persist($client);
            $em->flush();
            $this->addFlash('msg', 'client Modifié ');
            $this->addFlash('type', 'alert-success');
            return $this->render('BackBundle::client/show.html.twig', array('active' => 'vente',
                'client' => $client,
            ));


        }


        return $this->render('BackBundle::client/modifier.html.twig', array('active' => 'vente',
            'form' => $form->createView()
        ));
    }


    /**
     * Finds and displays a client entity.
     *
     * @Route("/new/client", name="Aclient_new")
     *
     */
    public function newAction()
    {

        $client=new Client();

        $request = $this->get('request');
        $form = $this->createForm(ClientType::class,$client);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


                $em = $this->getDoctrine()->getManager();

                $em->persist($client);
                $em->flush();

                $this->addFlash('msg', 'Client ajouté avec succes');
                $this->addFlash('type', 'alert-success');
                return $this->render('BackBundle::client/show.html.twig', array('active' => 'vente',
                    'client' => $client
                ));


        }

        return $this->render('BackBundle::client/new.html.twig', array('active' => 'vente',
            'form' => $form->createView()
        ));


    }
    /**
     * Finds and displays a client entity.
     *
     * @Route("/delete/{id}", name="Aclient_delete")
     *
     */
    public function deleteAction(Client $client)
    {


        $p = $this->getDoctrine()->getRepository('VenteBundle:Client')->find($client);
        $em = $this->getDoctrine()->getEntityManager();


        $em->remove($p);
        $em->flush();
        $this->addFlash('msg','Client supprimé');
        $this->addFlash('type','alert-success');


        return $this->redirect($this->generateUrl('Aclient_index'));

    }

}
