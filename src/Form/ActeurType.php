<?php

namespace App\Form;

use App\Entity\Acteur;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ActeurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('pronom')
            ->add('dateNaiss', null, [
                'widget' => 'single_text'
            ])
            ->add('sexe')
            ->add('nationality')
            ->add('image', FileType::class, [
                'label' => 'Image du film',
                'required' => false,
                'mapped' => false,
            ])
            ->add('films', EntityType::class, [
                'class' => Film::class,
                'choice_label' => 'titre',
                'multiple' => true,
                'expanded' => false,
                'by_reference' => false,  // Important for many-to-many relationships
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Acteur::class,
        ]);
    }
}
