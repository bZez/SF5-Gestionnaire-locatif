<?php

namespace App\Form;

use App\Entity\FaqCat;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FaqCatType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',null,['attr'=>['class'=>'input w-100 mb-3']])
            ->add('libelle',null,['attr'=>['class'=>'input w-100']])
            ->add('submit',SubmitType::class,['label'=>'Valider','attr'=>['class'=>'btn-rounded border-0 mt-3']])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FaqCat::class,
        ]);
    }
}
