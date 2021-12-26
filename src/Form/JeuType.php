<?php

namespace App\Form;

use App\Entity\Jeu;
use App\Entity\Evenement;
use App\Entity\Tournoi;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
class JeuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        
        $builder
            
            ->add('nomJeu')
            ->add('poule')
            ->add('equipeA')
            ->add('equipeB')
            ->add('pointEqA')
            ->add('pointEqB')
            ->add('save', SubmitType::class, ['label' => 'Créer le jeu !'])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Jeu::class,
        ]);
    }
}
