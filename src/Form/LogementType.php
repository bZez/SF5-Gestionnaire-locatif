<?php

namespace App\Form;

use App\Entity\Logement;
use App\Entity\Residence;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LogementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('residence',EntityType::class,['class'=>Residence::class,'choice_label'=>'nom'])
            ->add('situation',ChoiceType::class,[
                'choices'=>[
                    'Cours' => 'Cours',
                    'Jardin' => 'Jardin',
                    'Parking' => 'Parking',
                    'Rue' => 'Rue',
                    'Espace Vert' => 'Espace vert',
                    '2 Côtés' => '2 côtés',
                    'Traversans' => 'Traversans',
                    'Cours Nord' => 'Cours nord',
                    'Cours Sud' => 'Cours sud',
                    'Cours Est' => 'Cours est',
                    'Cours Ouest' => 'Cours ouest',
                ]
            ])
            ->add('type_lit',ChoiceType::class,[
                'choices'=>[
                    'Pas de Sommier' => 'Pas de sommier',
                    'Sommier 1 Place' => 'Sommier 1 place',
                    'Sommier 2 Places' => 'Sommier 2 places',
                ]
            ])
            ->add('type_logement',ChoiceType::class,[
                'choices'=>[
                    'F1' => 'F1',
                    'F1 BIS' => 'F1 BIS',
                    'F2' => 'F2',
                    'F2 COLOCATION' => 'F2 COLOCATION',
                    'F2 DUPLEX' => 'F2 DUPLEX',
                    'F3' => 'F3',
                    'F3 COLOCATION' => 'F3 COLOCATION',
                    'F4' => 'F4',
                    'F4 COLOCATION' => 'F4 COLOCATION',
                    'F5' => 'F5',
                    'F6' => 'F6',
                    'F7' => 'F7',
                    'F8' => 'F8',
                    'DUPLEX' => 'DUPLEX',
                ]
            ])
            ->add('etage',ChoiceType::class,[
                'choices'=>[
                    'RDC'=>'RDC',
                    'RDC Bas'=>'RDC bas',
                    'RDC Haut'=>'RDC haut',
                    '1er'=>'1er',
                    '2ème'=>'2ème',
                    '3ème'=>'3ème',
                    '4ème'=>'4ème',
                    '5ème'=>'5ème',
                    '6ème'=>'6ème',
                    '7ème'=>'7ème',
                    'Combles'=>'Combles',
                ]
            ])
            ->add('batiment')
            ->add('numero')
            ->add('categorie')
            ->add('surface')
            ->add('loyer')
            ->add('charges')
            ->add('cotis_acc')
            ->add('cotis_services')
            ->add('tva')
            ->add('date_blocage',DateType::class,['required'=>false,'widget' => 'single_text', 'attr' => ['class' => 'input col-md-12']])
            ->add('motif_blocage',TextType::class,['required'=>false])
            ->add(
                $builder->create('Clefs', FormType::class, ['inherit_data' => true])
                    ->add('cle_dispo',ChoiceType::class,['choices'=>[
                        'Oui' => 1,
                        'Non' => 0,
                    ]])
                    ->add('ref_cle')
                    ->add('qte_cle',NumberType::class)
                    ->add('qte_cle_bal',NumberType::class)
                    ->add('ref_cle_bal')
                    ->add('ref_cle_badge')
                    ->add('cle_residence',NumberType::class)
                    ->add('cle_local_velo',ChoiceType::class,['choices'=>[
                        'Oui' => 1,
                        'Non' => 0,
                    ]])
                    ->add('cle_salle_commune',ChoiceType::class,['choices'=>[
                        'Oui' => 1,
                        'Non' => 0,
                    ]])
                    ->add('cle_commentaire',TextType::class,['required'=>false])
                    ->add('date_maj_cle',DateType::class,['required'=>false,'widget' => 'single_text', 'attr' => ['class' => 'input col-md-12']]))

//            ->add('bloque_par')
//            ->add('maj_cle_par')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Logement::class,
        ]);
    }
}
