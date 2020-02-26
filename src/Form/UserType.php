<?php

namespace App\Form;

use App\Entity\Residence;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login')
            ->add('nom')
            ->add('prenom')
            ->add('email',EmailType::class)
            ->add('roles',ChoiceType::class,['choices'=>[
                'Animateur' =>'ROLE_ANIM',
                'Chargé de location' =>'ROLE_CL',
                'Adjoint administratif' =>'ROLE_ADJ',
                'Assistant de gestion' =>'ROLE_AG',
                'Administrateur' =>'ROLE_ADMIN',
            ],'multiple'=>true,'expanded'=>true])
            ->add('residences',EntityType::class,['class'=>Residence::class,'choice_label'=>'nom','multiple'=>true])
            ->add('plainPassword')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
