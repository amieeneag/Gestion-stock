<?php

namespace FactureBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/" ,name="facture")
     */
    public function indexAction()
    {
        return $this->render('FactureBundle::index.html.twig',array('active'=>'facturation'));
    }
}
