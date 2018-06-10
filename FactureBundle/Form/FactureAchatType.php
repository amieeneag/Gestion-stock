<?php

namespace FactureBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FactureAchatType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('total')
            ->add('date')
            ->add('modePaiement',ChoiceType::class,array(
                'choices'=>array('espèce'=>'espèce',
                    'cheque'=>'cheque')
            ))
            ->add('remise')
            ->add('description')
            ->add('commandeAchat')
            ->add('utilisateur');
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'FactureBundle\Entity\FactureAchat'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'facturebundle_factureachat';
    }


}
