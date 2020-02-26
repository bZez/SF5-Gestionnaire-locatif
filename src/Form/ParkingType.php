<?php

namespace App\Form;

use App\Entity\Parking;
use App\Entity\Residence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParkingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('num_place')
            ->add('situation',ChoiceType::class,['choices'=>[
                'Parking Aérien'=>'Aérien',
                'Parking Sous-Terrain'=>'Sous-Terrain'
            ]])
            ->add('residence',EntityType::class,['class'=>Residence::class,'choice_label'=>'nom'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Parking::class,
        ]);
    }
}
