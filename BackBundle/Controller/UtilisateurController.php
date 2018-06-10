<?php

namespace BackBundle\Controller;

use BackBundle\Entity\Utilisateur;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use BackBundle\Form\UtilisateurType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Response;


class UtilisateurController extends Controller
{
    /**
     * Lists all utilisateur entities.
     *
     * @Route("/admin/utilisateur", name="Autilisateur_index")
     * )
     */
    public function indexAction()
    {


        $form=$this->createFormBuilder()
            ->add('cherch','text',array('attr'=>array(
                'class'=>'form-control ',
                'placeholder'=>'Chercher...' ,
                'autocochmplete'=>'off')))
            ->add('choix',ChoiceType::class,array('choices'=>array(
                'nom'=>'Nom','prenom'=>'Prenom','username'=>'Username','role'=>'Role',
                'active'=>'Activé','desactive'=>'Desactivé',
            ),'attr'=>array('class'=>' ')
            ))

            ->add('chercher','submit')
            ->getForm();
        $request=$this->get('request');
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();

        if ($form->isSubmitted()) {


            $data = $form->getData();
            $choix=$data['choix'];
            $data=$data['cherch'];
            if($choix=='active'){
                $data=1;
            }
            if($choix=='desactive'){
                $choix="active";
                $data =0;

            }
            $email=$this->get('session')->get('email');

            $users=$this->getDoctrine()->getManager()
                ->createQuery("SELECT A FROM BackBundle:Utilisateur A WHERE
                                                                      A.$choix LIKE :nom
                                                                        ")


                ->setParameter('nom', '%'.$data.'%')->getResult();
            $utilisateurs = array();
            for($i=0;$i<count($users);$i++ ){
                if($users[$i]->getUsername() != $email){
                    $utilisateurs[]=$users[$i];
                }
            }


            if(!$utilisateurs ){
                $this->addFlash('msg',"Utilisateur n'existe pas ");
                $this->addFlash('type','alert-danger');}
            $t=count($utilisateurs);


            $this->addFlash('msg',"$t Utilisateur(s) Trouvé(s) ");
            $this->addFlash('type','alert-info');;
            return $this->render('BackBundle::utilisateur/index.html.twig', array('form'=> $form->createView(),
                'active' => 'admin',
                'utilisateurs' => $utilisateurs,
            ));

        }


        $user=$this->get('session')->get('email');

        $utilisateurs=$this->getDoctrine()->getManager()
            ->createQuery("SELECT A FROM BackBundle:Utilisateur A WHERE A.email NOT LIKE :nom")
        ->setParameter('nom', $user)->getResult();
        return $this->render('BackBundle::utilisateur/index.html.twig', array(
            'utilisateurs' => $utilisateurs,'active'=>'admin','form'=>$form->createView()
        ));
    }

    /**
     * Lists all utilisateur entities.
     *
     * @Route("/admin/utilisateur/{id}", name="Autilisateur_show")
     * )
     */
    public function showAction(Utilisateur $utilisateur)
    {

        $produit = $this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($utilisateur->getId());


        return $this->render('BackBundle::utilisateur/show.html.twig', array('active' => 'admin',
            'utilisateur' => $utilisateur,
        ));

    }
    /**
     * Lists all utilisateur entities.
     *
     * @Route("/utilisateur/modifier/{id}", name="Autilisateur_modifier")
     * )
     */
    public function modifierAction(Utilisateur $utilisateur)
    {


        $request = $this->get('request');
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if(!$this->get('session')->has('testemail')){
        $this->get('session')->set('testemail',$email=$utilisateur->getEmail() );}

        if ($form->isSubmitted()) {

            $em = $this->getDoctrine()->getManager();
            $username=$utilisateur->getEmail();
            $user=$this->get('session')->get('testemail');

            $usernames=$em->createQuery("SELECT A FROM BackBundle:Utilisateur A WHERE A.email LIKE :nom  ")
                ->setParameter('nom', $username)->getResult();


            if(!$usernames || $username==$user) {
                $em->persist($utilisateur);
                $em->flush();

                $this->addFlash('msg', 'Utilisateur Modifié ');
                $this->addFlash('type', 'alert-success');
                $this->get('session')->remove('testemail');
                return $this->render('BackBundle::utilisateur/show.html.twig', array('active' => 'admin',
                    'utilisateur' => $utilisateur
                ));
            }else{
                $this->addFlash('msg', 'Email entré existe déja');
                $this->addFlash('type', 'alert-danger');
            }





        }





        return $this->render('BackBundle::utilisateur/modifier.html.twig', array('active' => 'admin',
            'form' => $form->createView()
        ));

    }
    /**
     * Lists all utilisateur entities.
     *
     * @Route("/admin/utilisateur/delete/{id}", name="Autilisateur_delete")
     * )
     */
    public function deleteAction(Utilisateur $utilisateur)
    {

        $p = $this->getDoctrine()->getRepository('BackBundle:Utilisateur')->find($utilisateur->getId());
        $em = $this->getDoctrine()->getEntityManager();


        $em->remove($p);
        $em->flush();
        $this->addFlash('msg','Utilisateur supprimé');
        $this->addFlash('type','alert-success');


        return $this->redirect($this->generateUrl('Autilisateur_index'));

    }
    /**
     * Lists all utilisateur entities.
     *
     * @Route("/admin/new/utilisateur", name="Autilisateur_new")
     * )
     */
    public function newAction()
    {

        $utilisateur=new Utilisateur();

        $request = $this->get('request');
        $form = $this->createForm(UtilisateurType::class,$utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {


            $em = $this->getDoctrine()->getManager();
            $email=$utilisateur->getEmail();

            $usernames=$em->createQuery("SELECT A FROM BackBundle:Utilisateur A WHERE A.email LIKE :nom ")
                ->setParameter('nom', $email)->getResult();

            if(!$usernames) {


                $this->addFlash('msg', 'Utilisateur ajouté');
                $this->addFlash('type', 'alert-success');
                $msg=(new \Swift_Message('confirmaton'))
                         ->setFrom("amieene.ag@gmail.com")
                        ->setTo("$email")
                    ->setBody( $this->renderView( 'BackBundle::email.html.twig',
                        array('nom' => $utilisateur->getNom()." ".$utilisateur->getPrenom(),'login'=>$utilisateur->getEmail()
                        ,'password'=>$utilisateur->getPassword())),

                        'text/html');

                $x=$this->get('mailer')->send($msg);

                    $em->persist($utilisateur);
                $em->flush();
                return $this->render('BackBundle::utilisateur/show.html.twig', array('active' => 'admin',
                    'utilisateur' => $utilisateur
                ));
            }else{
                $this->addFlash('msg', 'Email entré existe déja');
                $this->addFlash('type', 'alert-danger');
            }

        }

        return $this->render('BackBundle::utilisateur/new.html.twig', array('active' => 'admin',
            'form' => $form->createView()
        ));


    }

    /**
     *
     *
     * @Route("/admin/moncompte", name="Autilisateur_compte")
     * )
     */
    public function compteAction()
    {
        $email=$this->get('session')->get('email');

        $u=$this->getDoctrine()->getRepository('BackBundle:Utilisateur')->findBy(array('email'=>$email));

        $id=$u['0']->getId();
        return $this->render('BackBundle::utilisateur/cshow.html.twig', array('active' => 'admin',
            'utilisateur' =>$u['0']
        ));

    }


    /**
     *
     *
     * @Route("/admin/moncompte/modifier", name="Autilisateur_comptemodifier")
     * )
     */
    public function comptemodAction()
    {
        $email=$this->get('session')->get('email');


        $utilisateur=$this->getDoctrine()->getRepository('BackBundle:Utilisateur')->findBy(array('email'=>$email))['0'];


        $request = $this->get('request');
        $form = $this->createForm(UtilisateurType::class, $utilisateur);
        $form->handleRequest($request);

        if(!$this->get('session')->has('testemail')){
            $this->get('session')->set('testemail',$email=$utilisateur->getEmail() );}

        if ($form->isSubmitted()) {



            $em = $this->getDoctrine()->getManager();
            $username=$utilisateur->getEmail();
            $user=$this->get('session')->get('testemail');



            $usernames=$em->createQuery("SELECT A FROM BackBundle:Utilisateur A WHERE A.email LIKE :nom  ")
                ->setParameter('nom', $username)->getResult();

            $utilisateur->setRole('ADMIN');
            if(!$usernames || $username==$user) {
                $em->persist($utilisateur);
                $em->flush();

                $this->addFlash('msg', 'Compte Modifié ');
                $this->addFlash('type', 'alert-success');
                $this->get('session')->remove('testemail');
                return $this->render('BackBundle::utilisateur/cshow.html.twig', array('active' => 'admin',
                    'utilisateur' => $utilisateur
                ));
            }else{
                $this->addFlash('msg', 'Email entré existe déja');
                $this->addFlash('type', 'alert-danger');
            }





        }







        return $this->render('BackBundle::utilisateur/cmodifier.html.twig', array('active' => 'admin',
            'form' => $form->createView()
        ));

    }





}
