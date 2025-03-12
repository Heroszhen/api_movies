<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints;
use Symfony\Component\Form\Extension\Core\Type\{CheckboxType, ChoiceType, CollectionType, TextType};

class MilleretRegistrationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email',null, [
                'empty_data' => '',
                'constraints'=> [
                    new Constraints\NotBlank(['message' => 'L\'email est obligatoire.'])
                ],
            ])
            ->add('lastname', null, [
                'empty_data' => '',
                'constraints'=> [
                    new Constraints\NotBlank(['message' => 'Le nom est obligatoire.'])
                ],
                'required'=>true
            ])
            // ->add('firstname', ChoiceType::class, [
            //     'choices' => ['Madame' => 'f', 'Monsieur' => 'm', 'Non spécifié' => 'un'],
            //     'multiple' => true,
            //     'expanded' => true
            // ])
            ->add('firstname', null, [
                'empty_data' => '',
                'constraints'=> [
                    new Constraints\NotBlank(['message' => 'Le nom est obligatoire.'])
                ],
                'required'=>true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'allow_extra_fields' => true, // Ignorer les champs non définis
        ]);
    }
}
