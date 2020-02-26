<?php

namespace App\Form;

use App\Entity\Contrat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ContratType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('debut')
            ->add('fin')
            ->add('date_gen')
            ->add('duree')
            /*->add('pdf')*/
            /*->add('universignTransId')
            ->add('universignSignId')*/
            ->add('signature')
            ->add('date_signature')
           /* ->add('gen_by')*/
            /*->add('locataire')*/
            /*->add('logement')*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Contrat::class,
        ]);
    }
}
