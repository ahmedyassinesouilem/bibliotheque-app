<?php
namespace App\Form;

use App\Entity\Auteur;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AuteurType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('biographie', TextareaType::class, [
                'attr' => [
                    'rows' => 5, // Nombre de lignes dans la zone de texte
                    'placeholder' => 'Entrez une biographie ici...',
                ],
            ])
            ->add('dateNaissance', null, [
                'widget' => 'single_text',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Auteur::class,
        ]);
    }
}
