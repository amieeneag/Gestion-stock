<?php

namespace StockBundle\Controller;

use BackBundle\Entity\MouvementProduit;
use StockBundle\Form\CategorieType;
use StockBundle\Form\MarqueType;
use Doctrine\DBAL\Types\BooleanType;
use Proxies\__CG__\StockBundle\Entity\Categorie;
use StockBundle\Entity\Produit;
use StockBundle\Repository\CategorieRepository;
use StockBundle\Repository\MarqueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use StockBundle\Form\ProduitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Validator\Constraints\Collection;
use Proxies\__CG__\StockBundle;

class ProduitController extends Controller
{
    /**
     * Lists all produit entities.
     *
     * @Route("/produit", name="produit_index")
     *
     */
    public function indexAction()
    {

        $form=$this->createFormBuilder()
            ->add('cherch','text',array('attr'=>array(
                'class'=>'form-control',
                 'required' => false,
                'placeholder'=>'Chercher' ,
                 'autocochmplete'=>'off')))
            ->add('type',ChoiceType::class,array(
                'choices'=>array('designation'=>'Designation', 'categorie'=>'Categorie' ,
                                    'categorie'=>'Categorie','marque'=>'Marque',

                    )
            ))

            ->add('chercher','submit')
            ->getForm();

        $request=$this->get('request');
        $form->handleRequest($request);


        if ($form->isSubmitted()) {


            $data = $form->getData();
            $type=$data['type'];
            $nom='%'.$data['cherch'].'%';



            if($type=="categorie" ) {

                $nom='%'.$data['cherch'].'%';
                $type=$data['type'];
                $produits = $this->getDoctrine()->getRepository('StockBundle:Produit')->createQueryBuilder('p')
                    ->innerjoin("p.$type", 'c')
                    ->select('p')
                    ->where("c.categorie LIKE '$nom' ")
                    ->getQuery()->getResult();


            }elseif($type=="marque"){

                $nom='%'.$data['cherch'].'%';

                $produits = $this->getDoctrine()->getRepository('StockBundle:Produit')->createQueryBuilder('p')
                    ->join("p.marque", 'm')
                    ->select('p')
                    ->where("m.marque LIKE :nom ")
                    ->setParameter('nom', $nom)
                    ->getQuery()->getResult();

            }
            else{

                $nom='%'.$data['cherch'].'%';
                if($data['type']=="active" && $data['cherch']=='oui' ){ $nom='1'; }
                if($data['type']=="active" && $data['cherch']=='no' ){ $nom='0'; }

                $produits = $this->getDoctrine()->getRepository('StockBundle:Produit')->createQueryBuilder('p')
                    ->select('p')
                    ->where("p.$type LIKE :nom ")
                    ->setParameter('nom',$nom)
                    ->getQuery()->getResult();

            }




            if(!$produits){
            $this->addFlash('msg',"Produit n'existe pas ");
                $this->addFlash('type','alert-danger');}
            $t=count($produits);


            $this->addFlash('msg',"$t Produit(s) Trouvé(s) ");
            $this->addFlash('type','alert-info');
            return $this->render('StockBundle::produit/index.html.twig', array('form'=> $form->createView(),
                'active' => 'entrepot',
                'produits' => $produits,
            ));

            }


        $produits = $this->getDoctrine()->getRepository('StockBundle:Produit')->findBy(array(), array('id' => 'desc'));

        return $this->render('StockBundle::produit/index.html.twig', array('form'=> $form->createView(),
            'active' => 'entrepot',
            'produits' => $produits,
        ));
    }

    /**
     * Finds and displays a produit entity
     *
     * @Route("/produit/{id}", name="produit_show")
     *
     */
    public function showAction(Produit $produit)
    {

        $produit = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($produit->getId());


        return $this->render('StockBundle::produit/show.html.twig', array('active' => 'entrepot',
            'produit' => $produit,
        ));
    }


    /**
     * Finds and displays a produit entity.
     *
     * @Route("/produit/modifier/{id}", name="produit_modifier")
     *
     */
    public function modifierAction(Produit $produit)
    {

        $p=new Produit();
        $p=$produit;
        $request = $this->get('request');
        $form = $this->createForm(ProduitType::class, $produit);

        $form->handleRequest($request);
        $this->get('session')->Set('imag',$produit->getImage());

        if ($form->isSubmitted()) {


            $data = $form->getData();

            if ($data->getFile()) {
                $dir = "/../web/bundles/image/";

                $brochureDir = $this->container->getParameter('kernel.root_dir') . $dir;
                $ext = $data->getFile()->guessExtension();
                $name = $produit->getId() . '.jpg';
                $data->getFile()->move($brochureDir, $name);
                $produit->setImage($name);

            }

            $prix=$produit->getPrixAchat()+$produit->getPrixAchat()*$produit->getmarge()/100;
            $produit->setPrixVente($prix);
            $em = $this->getDoctrine()->getManager();


            $em->persist($data);
            $em->flush();
            $this->addFlash('msg','Produit Modifié ');
            $this->addFlash('type','alert-success');
            return $this->render('StockBundle::produit/show.html.twig', array('active' => 'entrepot',
                'produit' => $data,
            ));


        }


        return $this->render('StockBundle::produit/modifier.html.twig', array('active' => 'entrepot',
            'form' => $form->createView(), 'img' => $produit->getImage()
        ));
    }


    /**
     * Finds and displays a produit entity.
     *
     * @Route("/produit/delete/{id}", name="produit_delete")
     * @Method("GET")
     */
    public function deleteAction(Produit $produit)
    {

        $p = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($produit->getId());
        $em = $this->getDoctrine()->getEntityManager();


        $em->remove($p);
        $em->flush();
        $this->addFlash('msg','Produit supprimé');
        $this->addFlash('type','alert-success');


        return $this->redirect($this->generateUrl('produit_index'));

    }

    /**
     * Finds and displays a produit entity.
     *
     * @Route("/new/produit", name="produit_new")
     *
     */
    public function newAction()
    {

        $produit=new Produit();

        $request = $this->get('request');
         $form = $this->createForm(ProduitType::class,$produit);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
;

            $dir = "/../web/bundles/image/";
            $em = $this->getDoctrine()->getManager();
            $brochureDir = $this->container->getParameter('kernel.root_dir') . $dir;
            $ext = $produit->getFile()->guessExtension();
            $em->persist($produit);
            $em->flush();

            $name = $produit->getId() . '.jpg';
            $produit->getFile()->move($brochureDir, $name);
            $produit->setImage($name);
            $prix=$produit->getPrixAchat()+$produit->getPrixAchat()*$produit->getmarge()/100;
            $produit->setPrixVente($prix);



            $em->persist($produit);
            $em->flush();
            $this->addFlash('msg','Produit ajouté avec succes');
            $this->addFlash('type','alert-success');
            return $this->render('StockBundle::produit/show.html.twig', array('active' => 'admin',
                'produit' => $produit
            ));


        }

        return $this->render('StockBundle::produit/new.html.twig', array('active' => 'admin',
            'form' => $form->createView()
        ));


    }

    /**
     * Finds and displays a produit entity
     *
     * @Route("/produit/{id}/entrer/{nb}", name="produit_entrer")
     *
     */
    public function entrerAction($id,$nb)
    {
        $produit=new Produit();
        $produit = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($id);
        $produit->setQuantiteStock($produit->getQuantiteStock()+$nb);
        $m=new MouvementProduit();
        $m->setProduit($produit);
        $m->setDate(new \DateTime());$m->setQuantite($nb);$m->setStatut('entré');
        $this->getDoctrine()->getManager()->persist($m);
        $this->getDoctrine()->getManager()->flush($m);

        $this->getDoctrine()->getManager()->persist($produit);
        $this->getDoctrine()->getManager()->flush($produit);

        return new JsonResponse([$produit->getQuantiteStock()]);


    }
    /**
     * Finds and displays a produit entity.->add('submit','submit',array('label'=>'Enregistrer','attr'=>array(
    'class'=>'btn-primary'
    )))
     *
     * @Route("/produit/{id}/sortir/{nb}", name="produit_sortir")
     *
     */
    public function sortirAction($id,$nb)
    {

        $produit=new Produit();
        $produit = $this->getDoctrine()->getRepository('StockBundle:Produit')->find($id);
        if($produit->getQuantiteStock()>=$nb) {
            $produit->setQuantiteStock($produit->getQuantiteStock() - $nb);
            $this->getDoctrine()->getManager()->persist($produit);
            $this->getDoctrine()->getManager()->flush($produit);
            $m=new MouvementProduit();
            $m->setProduit($produit);
            $m->setDate(new \DateTime());$m->setQuantite($nb);$m->setStatut('sorti');
            $this->getDoctrine()->getManager()->persist($m);
            $this->getDoctrine()->getManager()->flush($m);

            return new JsonResponse([$produit->getQuantiteStock()]);
        }else{

            return new JsonResponse(00);
        }
    }


}
