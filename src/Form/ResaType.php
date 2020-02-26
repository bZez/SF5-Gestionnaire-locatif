<?php

namespace App\Form;

use App\Entity\Prospect;
use App\Entity\Residence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ResaType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('locataire',FormType::class,['inherit_data'=>true])
                ->add('civilite',ChoiceType::class,['choices'=>['M'=>'M','Mme'=>'MME'],'attr'=>[
                    'class' =>'form-check-inline col-md-12',
                    'style' =>'font-size:xx-large'
                ],
                    'expanded' => true])
                ->add('nom',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Votre nom...','style' =>'font-size:xx-large']])
                ->add('prenom',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Votre prénom...','style' =>'font-size:xx-large']])
                ->add('date_naissance',DateType::class,['widget' => 'single_text','attr'=>['class'=>'input col-md-12','style' =>'font-size:xx-large']])
                ->add('telephone',TextType::class,['attr'=>['placeholder'=>'Téléphone...','class'=>'input col-md-12','style' =>'font-size:xx-large']])
                ->add('email',EmailType::class,['attr'=>['placeholder'=>'Adresse mail...','class'=>'input col-md-12','style' =>'font-size:xx-large']])
//                ->add('plainPassword', PasswordType::class, ['label' => 'Mot de passe'])
                ->add('adresse',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Votre adresse postale...','style' =>'font-size:xx-large']])
                ->add('ville',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Ville...','style' =>'font-size:xx-large']])
                ->add('code_postal',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Code postal...','style' =>'font-size:xx-large']])
                ->add('promo_mgel',ChoiceType::class,['choices'=>['Oui'=>'1','Non'=>'0'],'attr'=>[
                    'class' =>'input col-md-12',
                    'style' =>'font-size:xx-large'
                ]])
                ->add('promo_partenaire',ChoiceType::class,['choices'=>['Oui'=>'1','Non'=>'0'],'attr'=>[
                    'class' =>'input col-md-12',
                    'style' =>'font-size:xx-large'
                ]])
                ->add('residences',EntityType::class,['class'=>Residence::class,'choice_label'=>'nom','group_by'=>function($residence) {
                    return $residence->getVille()->getNom();
                },'multiple'=>true,'expanded'=>false,'label'=>false])
                ->add('date_entree',DateType::class,['widget' => 'single_text','attr'=>['class'=>'input col-md-12']])

//            ->add('date_sortie',DateType::class,['label'=>false,'widget' => 'single_text','attr'=>['class'=>'input col-md-12']])
//            ->add('statut',ChoiceType::class,['choices'=>['En attente'=>'EN ATTENTE','Accepté'=>'ACCEPTE']])
        )
            ->add(
                $builder->create('garant',FormType::class,['inherit_data'=>true])
                    ->add('civilite_garant',ChoiceType::class,['choices'=>['M'=>'M','MME'=>'MME'],'required'=>false])
                    ->add('nom_garant',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Nom du garant...'],'required'=>false])
                    ->add('prenom_garant',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Prénom du garant...'],'required'=>false])
                    ->add('date_naissance_garant',DateType::class,['widget' => 'single_text','attr'=>['class'=>'input col-md-12'],'required'=>false])
                    ->add('telephone_garant',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Téléphone du garant...'],'required'=>false])
                    ->add('email_garant',EmailType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Email du garant...'],'required'=>false])
                    ->add('adresse_garant',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Adresse postale du garant...'],'required'=>false])
                    ->add('ville_garant',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Ville du garant...'],'required'=>false])
                    ->add('code_postal_garant',TextType::class,['attr'=>['class'=>'input col-md-12','placeholder'=>'Code postal...'],'required'=>false])
            )
        ;

            /*->add('date_recep')
            ->add('date_accep')*/
            /*->add('accept_by',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'NomPrenom'
            ])*/
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
