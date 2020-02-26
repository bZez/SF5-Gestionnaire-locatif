<?php

namespace App\Form;

use App\Entity\EDL;
use App\Entity\Logement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EDLType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type',ChoiceType::class,['choices'=>[
                "Etat des lieux d'ENTREE"=>"EDLE",
                "Etat des lieux de SORTIE"=>"EDLS",
            ]])
            ->add('date')
            ->add('logement', EntityType::class, ['class' => Logement::class, 'choice_label' => function ($choiceValue) {
                return $choiceValue->getInfos();
            }
                , 'group_by' => function ($choiceValue) {
                    if ($choiceValue->getOccupation() !== true) {
                        return $choiceValue->getResidence()->getNom();
                    } else {
                        return 'Occupé ' . $choiceValue->getResidence()->getNom();
                    }
                }])
           /* ->add('doc_manquant',ChoiceType::class,['choices'=>[
                "Attestation d'assurance"=>"att_assur",
                "Contrat"=>"contrat",
                "Acte de caution"=>"act_caution",
                "Chèque de dépôt de garantie"=>"cheque",
                "Autorisation de prélèvement"=>"aut_prelev",
                "RIB"=>"rib",
                "Photo"=>"photo",
                "Règlement adhésion"=>"rgt_adh",
            ],'multiple'=>true,'expanded'=>true,'attr'=>[
                'class'=>'form-check-inline text-center'
            ],'label_attr'=>[
                'class'=>'form-inline'
            ]])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => EDL::class,
        ]);
    }
}
