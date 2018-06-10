<?php

namespace StockBundle\Form;

use Doctrine\DBAL\Types\FloatType;
use StockBundle\Entity\Categorie;
use StockBundle\Entity\Marque;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use BackBundle\Form\CategorieType;
use BackBundle\Form\MarqueType;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use StockBundle\Repository\CategorieRepository;

class ProduitType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('designation')
            ->add('description')

            ->add('categorie',EntityType::class,array(
                'class'=>'StockBundle:Categorie',
                'placeholder'=>'Choisir Marque'))
            ->add('marque',EntityType::class,array(
                'class'=>'StockBundle:Marque',
                'placeholder'=>'Choisir Marque',
                'choice_attr' => function ( $key,$val,$index) { return ["data-id" =>$key->getCategorie()->getId()]; },

            ))
            ->add('prixAchat')
            ->add('marge')
            ->add('tva')

            ->add('image')
            ->add('file',FileType::class,array('label'=>'Ajouter image',
                'attr'=>array('accept'=>'image/*')))

            ->add('quantiteStock')
            ->add('quantiteSeuil')

            ->add('active')


            ->add('submit',SubmitType::class,array('label'=>'Enregistrer','attr'=>array('class'=>'btn btn-success')));
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'StockBundle\Entity\Produit'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'stockbundle_produit';
    }


}
