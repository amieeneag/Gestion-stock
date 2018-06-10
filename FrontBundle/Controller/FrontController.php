<?php

namespace FrontBundle\Controller;

use BackBundle\BackBundle;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\BrowserKit\Request;
use Symfony\Component\Form\Tests\Extension\Core\Type\SubmitTypeTest;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Serializer\Encoder\JsonEncode;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;


class FrontController extends Controller
{
    /**
     * @Route("/" ,name="connexion")
     */
    public function indexAction()
    {

        $form = $this->createFormBuilder()
            ->add('email', TextType::class, array('label' => 'Email :', 'attr' => array('autocomplete' => "off")))
            ->add('password', PasswordType::class, array('label' => 'Password :', 'attr' => array('autocomplete' => 'off')))
            ->add('Connexion', SubmitType::class, array('attr' => array('class' => 'btn-primary col-md-12')))
            ->getForm();
        $request = $this->get('request');

        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $data = $form->getData();

            $email = $data['email'];
            $pass = $data['password'];

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository("BackBundle:Utilisateur")
                ->findOneBy(array('email' => $email, 'password' =>"amine"));
         //      ->findOneBy(array('email' => $email, 'password' =>convert_uuencode($pass)));
            $session=$this->get('session');


            if ($user) {

                if($user->getActive()==1) {

                    $session->Set('role', $user->getRole());
                    $session->set('userId',$user->getId());
                    $session->Set('nom', $user->getNom());
                    $session->Set('email', $email);
                    $session->Set('test', 1);

                }else{
                    $session->getFlashBag()->add('notice', "compte desactivé, veuillez contacter le directeur");
                    return $this->render("FrontBundle::connexion.html.twig", array('form' => $form->createView()));
                }


                return $this->redirect($this->generateUrl('entrepot'));

            }

            $session->getFlashBag()->add('notice', 'Email ou Password est incorrect');


        }

        return $this->render("FrontBundle::connexion.html.twig", array('form' => $form->createView()));
    }





    /**
     * @Route("/logout" ,name="logout")
     */
    public function deconnexionAction()
    {
        $session=$this->get('session');


        $session->clear();

        return $this->redirect($this->generateUrl("connexion"));
    }



}