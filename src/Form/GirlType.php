<?php

namespace App\Form;

use App\Entity\DataPrototype\Girl;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class GirlType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', null, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Nom*'
            ])
            ->add('height', null, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Taille*'
            ])
            ->add('age', null, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Age*'
            ])
            ->add('job', null, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Profession*'
            ])
            ->add('cup', null, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Taille de bonnet*'
            ])
            ->add('type', null, [
                'attr' => ['class' => 'form-control'],
                'constraints' => [new Assert\NotBlank()],
                'label' => 'Type*'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Girl::class,
        ]);
    }
}
