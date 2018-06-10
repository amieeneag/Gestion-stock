<?php

namespace BackBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('prenom')
            ->add('statut',ChoiceType::class,array('choices'=>array(
                'particulier'=>'Particulier',
                'société'=>"Société"
            )))
            ->add('telephone')
            ->add('raisonSocial')->add('email')
            ->add('adresse')->add('active')
            ->add('submit','submit',array('label'=>'Enregistrer','attr'=>array(
                'class'=>'btn-primary'
            )))    ;
    }
    
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'VenteBundle\Entity\Client'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'ventebundle_client';
    }


}
