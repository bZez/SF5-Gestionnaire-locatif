<?php

namespace App\Form;

use App\Entity\Photos;
use App\Entity\Residence;
use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResidenceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom')
            ->add('adresse')
            ->add('ville',EntityType::class,['class'=>Ville::class,'choice_label'=>'nom'])
            ->add('lattitude')
            ->add('longitude')
            ->add('intro')
            ->add('conso_ener')
            ->add('emi_gaz')
            ->add('services', ChoiceType::class, ['choices' =>
                ['Animateur' => 'anim',
                    'Buanderie' => 'buanderie',
                    'Salle de sport' => 'sport',
                    'Salle de Télévision' => 'tv',
                    'Salle de Coworking' => 'cowork',
                    'Salle de Détente' => 'detente',
                    'Petit déjeuner' => 'petitdej',
                    'Internet' => 'internet',
                    'Sauna' => 'sauna',
                    'Local à vélos' => 'velos',
                    'Vidéo-surveillance' => 'video',
                    'Prêt de matériel' => 'pret',
                ], 'multiple' => true,'expanded' => true,'attr'=>[
                    'class'=>'form-check-inline text-center'
            ],'label_attr'=>[
                'class'=>'form-inline'
            ]])
            ->add('couverture',FileType::class,['data_class' => null])
            ->add('photos',PhotosType::class,['data_class'=>null])
            ->add('video')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Residence::class,
        ]);
    }
}
