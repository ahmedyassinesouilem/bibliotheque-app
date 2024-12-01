<?php

namespace App\Form;

use App\Entity\Auteur;
use App\Entity\Genre;
use App\Entity\Livre;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;

class LivreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('titre')
            ->add('isbn')
            ->add('disponible')
            ->add('image', FileType::class, [
                'label' => 'Image du Livre (fichier PNG ou JPG)',
                'mapped' => false, // Ne mappe pas directement à l'entité
                'required' => false,
            ])
            ->add('auteur', EntityType::class, [
                'class' => Auteur::class,
                'choice_label' => 'nom',
                'multiple' => true,
            ])
            ->add('genre', EntityType::class, [
                'class' => Genre::class,
                'choice_label' => 'nom',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Livre::class,
        ]);
    }
}
