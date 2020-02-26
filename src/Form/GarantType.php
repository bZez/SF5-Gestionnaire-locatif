<?php

namespace App\Form;

use App\Entity\Garant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GarantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', ChoiceType::class, ['choices' => [
                'M' => 'M',
                'MME' => 'MME'
            ],
                'attr' => ['class' => 'col-md-12']])
            ->add('nom')
            ->add('prenom')
            ->add('date_naissance', DateType::class, ['widget' => 'single_text', 'attr' => ['class' => 'input col-md-12']])
            ->add('adresse')
            ->add('cpl_adresse')
            ->add('ville')
            ->add('code_postal')
            ->add('tel_fixe')
            ->add('pays',CountryType::class)
            ->add('tel_mobile')
            ->add('email')
            ->add(
                $builder->create('documents',FormType::class,['inherit_data'=>true])
                    ->add('cni',FileType::class,['data_class' => null])
                    ->add('livret',FileType::class,['data_class' => null])
                    ->add('justif_dom',FileType::class,['data_class' => null])
                    ->add('bull_sal',FileType::class,['data_class' => null])
                    ->add('avis_imp',FileType::class,['data_class' => null])
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Garant::class,
        ]);
    }
}
