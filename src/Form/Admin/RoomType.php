<?php

namespace App\Form\Admin;

use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'title', type: TextType::class, options: [
                'label' => "Titre de la chambre :",
                'attr' => [
                    'placeholder' => "Saisir le titre de la chambre",
                ]
            ])
            ->add(child: 'shortDescription', type: TextType::class, options: [
                'label' => "Description courte de la chambre :",
                'attr' => [
                    'placeholder' => "Saisir une description courte pour la chambre",
                ]
            ])
            ->add(child: 'longDescription', type: TextareaType::class, options: [
                'label' => "Description longue de la chambre :",
                'attr' => [
                    'placeholder' => "Saisir une description longue pour la chambre",
                ]
            ])
            ->add('imageFile', VichImageType::class, [
                'required' => in_array('create', $options['validation_groups'] ?? []),
                'allow_delete' => false,
                'label' => "Image pour le slider",
            ])
            ->add(child: 'night', type: MoneyType::class, options: [
                'label' => "Prix de la chambre :",
                'attr' => [
                    'placeholder' => "Saisir le prix pour la chambre",
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Room::class,
        ]);
    }
}
