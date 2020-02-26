<?php

namespace App\Form;

use App\Entity\CourtSejour;
use App\Entity\Residence;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourtSejourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom',TextType::class,['attr'=>['placeholder'=>'Votre nom...']])
            ->add('prenom',TextType::class,['attr'=>['placeholder'=>'Votre prénom...']])
            ->add('email',EmailType::class,['attr'=>['placeholder'=>'Votre adresse mail...']])
            ->add('mobile',TelType::class,['attr'=>['placeholder'=>'Votre numéro de mobile...']])
            ->add('date_entree',DateType::class,['widget' => 'single_text'])
            ->add('date_sortie',DateType::class,['widget' => 'single_text'])
            ->add('ville',EntityType::class,['class'=>Ville::class,'choice_label'=>'nom'])
            ->add('residence',EntityType::class,['class'=>Residence::class,
                'choice_label'=>'nom',
                'group_by'=>function($choice){
                return $choice->getVille()->getNom();
            },
                'attr'=>[
                    'disabled' => true
                ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourtSejour::class,
        ]);
    }
}
