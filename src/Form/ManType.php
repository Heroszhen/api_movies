<?php

namespace App\Form;

use App\Entity\DataPrototype\Man;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\GirlType;

class ManType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('height')
            ->add('age')
            ->add('job')
            ->add('girlFriends', CollectionType::class, [
                'entry_type' => GirlType::class,
                'by_reference' => false, //pour utiliser les méthodes add et remove
                'allow_add' => true,   // Allow adding new entries dynamically
                'allow_delete' => true, // Allow removing entries dynamically
                'entry_options' => ['label' => false], // supprimer collection indexs
                // 'attr' => [
                //     'data-controller' => 'form-collection',
                //     'data-form-collection-add-label-value' => 'Ajouter',
                //     'data-form-collection-delete-label-value' => 'Supprimer'
                // ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Man::class,
        ]);
    }
}
