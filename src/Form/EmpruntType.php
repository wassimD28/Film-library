<?php

namespace App\Form;

use App\Entity\Adherent;
use App\Entity\Emprunt;
use App\Entity\Film;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmpruntType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dhEmprunt', null, [
                'widget' => 'single_text'
            ])
            ->add('dhRetour', null, [
                'widget' => 'single_text'
            ])
            ->add('film', EntityType::class, [
                'class' => Film::class,
'choice_label' => 'id',
            ])
            ->add('adherent', EntityType::class, [
                'class' => Adherent::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Emprunt::class,
        ]);
    }
}
