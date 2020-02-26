<?php

namespace App\Form;

use App\Entity\Logement;
use App\Entity\Voeu;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoeuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('annee',TextType::class, ['attr'=>['readonly'=>true]])
            ->add('souhait',ChoiceType::class,['choices'=>[
                'Pas de réponse' => 'Pas de réponse',
                'Ne sais pas' => 'Ne sais pas',
                'Reste' => 'Reste',
            ]])
            ->add('logement', EntityType::class, ['class' => Logement::class, 'choice_label' => function ($choiceValue) {
                return $choiceValue->getInfos();
            },
                'attr'=>
            [
                'readonly'=>true
            ]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Voeu::class,
        ]);
    }
}
