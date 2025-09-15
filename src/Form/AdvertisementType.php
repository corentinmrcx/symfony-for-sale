<?php

namespace App\Form;

use App\Entity\Advertisement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AdvertisementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', 'string', ['attr' => ['minlength' => 10, 'maxlength' => 100]])
            ->add('description', 'string', ['attr' => ['minlength' => 20, 'maxlength' => 1000]])
            ->add('price', 'float', ['attr' => ['min' => 0]])
            ->add('location', 'string', ['attr' => ['minlength' => 2, 'maxlength' => 100]])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Advertisement::class,
        ]);
    }
}
