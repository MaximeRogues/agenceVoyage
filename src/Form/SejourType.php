<?php

namespace App\Form;

use App\Entity\Sejour;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SejourType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('type_logement')
            ->add('nb_personne')
            ->add('titre')
            ->add('description')
            ->add('category')
            ->add('destination')
            ->add('activity')
            ->add('Envoi', SubmitType::class)

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Sejour::class,
        ]);
    }
}
