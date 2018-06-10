<?php

namespace BackBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Ob\HighchartsBundle\Highcharts\Highchart;

class DefaultController extends Controller
{

    /**
     *
     *
     * @Route("/suivi/activite", name="suivi")
     *
     */
    public function indexAction()
    {

        return $this->render('BackBundle::suivi/suivi.html.twig',
            array('active'=>'suivi'));

    }


    /**
     * Lists all utilisateur entities.
     *
     * @Route("/suivi/vente/cmd", name="suiviventecmd")
     * )
     */
    public function cmdvAction()
    {


        $cmd=$this->getDoctrine()->getRepository('VenteBundle:CommandeVente')->findBy(array(),array('date'=>'desc'));

        $form = $this->createFormBuilder()
            ->add('date')
            ->add('submit','submit',array('label'=>'chercher'))
            ->getForm();

        $request = $this->get('request');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $data=$form->getData();
            $date=$data['date'];
            $cmd= $this->getDoctrine()->getManager()
                ->createQuery("SELECT A FROM VenteBundle:CommandeVente A
                                     WHERE A.date LIKE :dt
                                      ")
                ->setParameter('dt', "%".$date."%")

                ->getResult();

            return $this->render('BackBundle::suivi/suiviV.html.twig',
                array('active'=>'suivi','cmd'=>$cmd,'form'=>$form->createView()));

        }

        return $this->render('BackBundle::suivi/suiviV.html.twig',
            array('active'=>'suivi','cmd'=>$cmd,'form'=>$form->createView()));


    }



    /**
     * Lists all utilisateur entities.
     *
     * @Route("/suivi/liv/bon", name="suivilivraisonbon")
     *
     */
    public function livsuivibonAction()
    {


        $cmd=$this->getDoctrine()->getRepository('AchatBundle:CommandeAchat')->findBy(array(),array('date'=>'desc'));


        $form = $this->createFormBuilder()
            ->add('date')
            ->add('submit','submit',array('label'=>'chercher'))
            ->getForm();

        $request = $this->get('request');
        $form->handleRequest($request);
        if ($form->isSubmitted()) {

            $data=$form->getData();
            $date=$data['date'];
            $cmd= $this->getDoctrine()->getManager()
                ->createQuery("SELECT A FROM AchatBundle:CommandeAchat A
                                     WHERE A.date LIKE :dt
                                      ")
                ->setParameter('dt', "%".$date."%")

                ->getResult();

            return $this->render('BackBundle::suivi/suivilivbon.html.twig',
                array('active'=>'suivi','cmd'=>$cmd,'form'=>$form->createView()));

        }


        return $this->render('BackBundle::suivi/suivilivbon.html.twig',
            array('active'=>'suivi','cmd'=>$cmd,'form'=>$form->createView()));


    }



    /**
     * Lists all utilisateur entities.
     *
     * @Route("/suivi/chart", name="charte")
     *
     */
    public function chartAction()
    {


            $date = new \DateTime();
            $mois = $date->format('m');

            $annee = $date->format('y');
            $d="$annee-$mois-";





        $data=array();






        $data[]=0;
        $z=0;
        $t=0;
        $total=0;


        $nb=$this->getDoctrine()->getManager()
            ->createQuery("SELECT A FROM VenteBundle:CommandeVente A WHERE A.date LIKE :d

                                      ")
            ->setParameter('d', "20".$d."%")
            ->getResult();
        if($nb) {
            $nb = $nb[count($nb) - 1]->getDate()->format('d');


        for($i=1;$i<31;$i++){
            if($nb=="0$i"){
                $nb=$i;
            }
        }

        for($i=0;$i<$nb;$i++){
            $tot=0;
            $t=$t+1;
            if($t<10){
                $t='0'.$t;
            }


            $cmd = $this->getDoctrine()->getManager()
                ->createQuery("SELECT A FROM VenteBundle:CommandeVente A WHERE A.date LIKE :d

                                      ")
                ->setParameter('d', "20".$d.$t."%")
                ->getResult();

            if($cmd) {
                for ($y = 0; $y < count($cmd); $y++) {
                    $tot=$tot+$cmd[$y]->getTotal();

                }

            }

            $data[]=$tot;
            $total=$total+$tot;

        }}


        $series = array(
            array("name" => "Chiffre d'affaire",    "data" => $data)
        );

        $ob = new Highchart();
        $ob->chart->renderTo('chiffre');  // The #id of the div where to render the chart
        $ob->title->text("Chiffre D'affaire ($mois-20$annee) ");
        $ob->xAxis->title(array('text'  => "jour"));
        $ob->yAxis->title(array('text'  => "Dh"));
        $ob->series($series);

        return $this->render('BackBundle::suivi/chart.html.twig', array(
            'chart' => $ob,'active'=>'suivi','date'=>new \DateTime(),'total'=>$total
        ));


    }



}
