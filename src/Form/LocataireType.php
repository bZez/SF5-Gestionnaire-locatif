<?php

namespace App\Form;

use App\Entity\Garant;
use App\Entity\Locataire;
use App\Entity\Logement;
use App\Repository\LocataireRepository;
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

class LocataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civilite', ChoiceType::class, ['choices' => [
                'M' => 'M',
                'MME' => 'MME'
            ],
                'attr' => ['class' => 'col-md-12']])
            ->add('nom', null, ['attr' => ['class' => 'col-md']])
            ->add('prenom', null, ['attr' => ['class' => 'col-md']])
            ->add('date_naissance', DateType::class, ['widget' => 'single_text', 'attr' => ['class' => 'input col-md-12']])
            ->add('cheque_frais', ChoiceType::class, ['choices' => [
                'Reçu' => '1',
                'En attente...' => '0'
            ]])
            ->add('code_insee')
            ->add('externe', ChoiceType::class, ['choices' => [
                'Oui' => '1',
                'Non' => '0'
            ]])
            ->add('etranger', ChoiceType::class, ['choices' => [
                'Oui' => '1',
                'Non' => '0'
            ]])
            ->add('type_adh', ChoiceType::class, ['choices' => [
                'Non adhérent' => 'Non adhérent',
                'Adhérent MGEL' => 'Adhérent MGEL',
                'MGEL Logement' => 'MGEL Logement']])
            ->add('type_mrh', ChoiceType::class, ['choices' => [
                'Autre' => 'Autre',
                'Vital Assur' => 'Vital Assur']])
            ->add('cnil_mgel', ChoiceType::class, ['choices' => [
                'Oui' => '1',
                'Non' => '0'
            ]])
            ->add('cnil_partenaires', ChoiceType::class, ['choices' => [
                'Oui' => '1',
                'Non' => '0'
            ]])
            ->add('ville_naissance')
            ->add('adresse')
            ->add('cpl_adresse')
            ->add('ville')
            ->add('code_postal')
            ->add('tel_fixe')
            ->add('tel_mobile')
            ->add('email', EmailType::class)
            /*->add('plainPassword', PasswordType::class, ['label' => 'Mot de passe généré', 'attr' => [
                'value' => $this->generatePassword(),
            ]])*/
            /*->add('logement',TextType::class,['data'=>function ($logement){
                return $this-);
            }])*/
//            ->add('voeu')
            ->add(
                $builder->create('documents', FormType::class, ['inherit_data' => true])
                    ->add('cni', FileType::class, ['data_class' => null])
                    ->add('justif_bourse', FileType::class, ['data_class' => null])
                    ->add('cheque_frais', ChoiceType::class, ['choices' => ['Chèque reçu' => '1', 'Chèque en attente...' => '0']]))
            ->add('commentaire');;

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
            'data_class' => Locataire::class,
        ]);
    }
}
