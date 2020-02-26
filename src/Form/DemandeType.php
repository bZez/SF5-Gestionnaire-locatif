<?php

namespace App\Form;

use App\Entity\Demande;
use App\Entity\Prospect;
use App\Entity\Residence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DemandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('residences',EntityType::class,['class'=>Residence::class,'choice_label'=>'nom'])
            ->add('civilite')
            ->add('cni',FileType::class)
            ->add('cni_garant',FileType::class)
            ->add('justif_dom',FileType::class)
            ->add('livret',FileType::class)
            ->add('bull_sal',FileType::class)
            ->add('avis_imp',FileType::class)
            ->add('nom',TextType::class,['attr'=>['class'=>'input']])
            ->add('prenom',TextType::class,['attr'=>['class'=>'input']])
            ->add('telephone',TextType::class,['attr'=>['type'=>'tel']])
            ->add('email',TextType::class,['attr'=>['class'=>'input']])
            ->add('adresse',TextType::class,['attr'=>['class'=>'input']])
            ->add('ville',TextType::class,['attr'=>['class'=>'input']])
            ->add('code_postal',TextType::class,['attr'=>['class'=>'input']])
            ->add('civilite_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('nom_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('prenom_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('telephone_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('email_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('adresse_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('ville_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('code_postal_garant',TextType::class,['attr'=>['class'=>'input']])
            ->add('promo_mgel',ChoiceType::class,['choices'=>[
                'Oui' => 1,
                'Non' => 0
            ],'attr'=>['class'=>'input']])
            ->add('promo_partenaire',ChoiceType::class,['choices'=>[
                'Oui' => 1,
                'Non' => 0
            ],'attr'=>['class'=>'input']])
            ->add('date_entree',DateType::class,['label'=>false,'widget' => 'single_text','attr'=>['class'=>'input col-md-12']])
            ->add('date_sortie',DateType::class,['label'=>false,'widget' => 'single_text','attr'=>['class'=>'input col-md-12']])
            ->add('submit',SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
