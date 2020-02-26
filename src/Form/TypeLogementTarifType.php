<?php

namespace App\Form;

use App\Entity\TypeLogementTarif;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TypeLogementTarifType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('categorie')
            ->add('loyer')
            ->add('charges')
            ->add('cotis_acc')
            ->add('cotis_services')
            ->add('residence')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TypeLogementTarif::class,
        ]);
    }
}
