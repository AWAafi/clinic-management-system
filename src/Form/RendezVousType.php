<?php

namespace App\Form;

use App\Entity\RendezVous;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RendezVousType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('heure')
            ->add('date')
            ->add('motif', ChoiceType::class, [
                'choices' => [
                    'Consultation Générale' => 'consultation_generale',
                    'Consultation Spécialisée' => 'consultation_specialisee',
                    'Vaccinations' => 'vaccinations',
                    'Santé Mentale' => 'sante_mentale',
                    'Examens et Tests' => 'examens_tests',
                    'Consultation Préventive' => 'consultation_preventive',
                    'Santé Reproductive' => 'sante_reproductive',
                ],
                'placeholder' => 'Sélectionnez un motif',
                'label' => 'Motif',
            ])
            
            ->add('enregistrer', SubmitType::class, ['label' => 'Enregistrer'])
            

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'data_class' => RendezVous::class,
        ]);
    }
}
