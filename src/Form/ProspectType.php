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

class ProspectType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            $builder->create('locataire',FormType::class,['inherit_data'=>true])
                ->add('civilite',ChoiceType::class,['choices'=>['Monsieur'=>'M','Madame'=>'MME']])
                ->add('nom',TextType::class,['attr'=>['class'=>'input col-md-12']])
                ->add('prenom',TextType::class,['attr'=>['class'=>'input col-md-12']])
                ->add('date_naissance',DateType::class,['widget' => 'single_text','attr'=>['class'=>'input col-md-12']])
                ->add('telephone',TextType::class,['attr'=>['class'=>'input col-md-12']])
                ->add('email',EmailType::class,['attr'=>['class'=>'input col-md-12','onfocusout'=>'checkMail($(this).val())']])
                ->add('plainPassword', PasswordType::class, ['label' => 'Mot de passe généré', 'attr' => [
                    'value' => $this->generatePassword(),
                ]])
                ->add('adresse',TextType::class,['attr'=>['class'=>'input col-md-12']])
                ->add('ville',TextType::class,['attr'=>['class'=>'input col-md-12']])
                ->add('code_postal',TextType::class,['attr'=>['class'=>'input col-md-12']])
                ->add('promo_mgel',ChoiceType::class,['choices'=>['Oui'=>'1','Non'=>'0']])
                ->add('promo_partenaire',ChoiceType::class,['choices'=>['Oui'=>'1','Non'=>'0']])
                ->add('residences',EntityType::class,['class'=>Residence::class,'choice_label'=>'nom','multiple'=>true,'expanded'=>false])
                ->add('date_entree',DateType::class,['widget' => 'single_text','attr'=>['class'=>'input col-md-12']])

//            ->add('date_sortie',DateType::class,['label'=>false,'widget' => 'single_text','attr'=>['class'=>'input col-md-12']])
//            ->add('statut',ChoiceType::class,['choices'=>['En attente'=>'EN ATTENTE','Accepté'=>'ACCEPTE']])
        )
            ->add(
                $builder->create('garant',FormType::class,['inherit_data'=>true])
                    ->add('civilite_garant',ChoiceType::class,['choices'=>['M'=>'M','MME'=>'MME']])
                    ->add('nom_garant',TextType::class,['attr'=>['class'=>'input col-md-12']])
                    ->add('prenom_garant',TextType::class,['attr'=>['class'=>'input col-md-12']])
                    ->add('date_naissance_garant',DateType::class,['widget' => 'single_text','attr'=>['class'=>'input col-md-12']])
                    ->add('telephone_garant',TextType::class,['attr'=>['class'=>'input col-md-12']])
                    ->add('email_garant',EmailType::class,['attr'=>['class'=>'input col-md-12']])
                    ->add('adresse_garant',TextType::class,['attr'=>['class'=>'input col-md-12']])
                    ->add('ville_garant',TextType::class,['attr'=>['class'=>'input col-md-12']])
                    ->add('code_postal_garant',TextType::class,['attr'=>['class'=>'input col-md-12']])
            )
            ->add(
                $builder->create('documents',FormType::class,['inherit_data'=>true])
                    ->add('cni',FileType::class,['data_class' => null])
                    ->add('justif_bourse',FileType::class,['data_class' => null])
                    ->add('cheque_frais',ChoiceType::class,['choices'=>['Chèque reçu'=>'1','Chèque en attente'=>'0']])
                    ->add('cni_garant',FileType::class,['data_class' => null])
                    ->add('livret',FileType::class,['data_class' => null])
                    ->add('justif_dom',FileType::class,['data_class' => null])
                    ->add('bull_sal',FileType::class,['data_class' => null])
                    ->add('avis_imp',FileType::class,['data_class' => null])
            );
        ;

            /*->add('date_recep')
            ->add('date_accep')*/
            /*->add('accept_by',EntityType::class,[
                'class'=>User::class,
                'choice_label'=>'NomPrenom'
            ])*/
        ;

    }
    public function generatePassword()
    {
        $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyz$';
// Output: 54esmdr0qf
        return substr(str_shuffle($permitted_chars), 0, 10);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prospect::class,
        ]);
    }
}
