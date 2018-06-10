<?php

namespace FactureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;

use Symfony\Component\Form\Tests\Extension\Core\Type\NumberTypeTest;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureVenteType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('date')->add('total')
            ->add('modePaiement',ChoiceType::class,array(
                'choices'=>array('Espece'=>'Espece' ,'Cheque'=>'Cheque')
            ))
            ->add('remise')
            ->add('description')
            ->add('commandeVente')
            ->add('utilisateur');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FactureBundle\Entity\FactureVente'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'facturebundle_facturevente';
    }


}
