<?php

namespace App\Form;

use App\Entity\Acteur;
use App\Entity\Categorie;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('dateSortie', null, [
                'widget' => 'single_text'
            ])
            ->add('description')
            ->add('couverture', FileType::class, [
                'label' => 'Image du film',
                'required' => false,
                'mapped' => false,
            ])
            ->add('disponible')
            ->add('views')
            ->add('rating')
            ->add('trailer')
            ->add('categorie', EntityType::class, [
                'class' => Categorie::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Film::class,
        ]);
    }
}
