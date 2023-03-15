<?php

namespace App\Form\Admin;

use App\Entity\Booking;
use App\Entity\Room;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class BookingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'startsAt', type: DateType::class, options: [
                'label' => "Date d'arrivée",
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ])
            ->add(child: 'endsAt', type: DateType::class, options: [
                'label' => "Date de départ",
                'widget' => 'single_text',
                'input'  => 'datetime_immutable',
            ])
            ->add(child: 'firstName', type: TextType::class, options: [
                'label' => "Prénom du client :",
                'attr' => [
                    'placeholder' => "Saisir le prénom du client",
                ]
            ])
            ->add(child: 'lastName', type: TextType::class, options: [
                'label' => "Nom du client :",
                'attr' => [
                    'placeholder' => "Saisir le nom du client",
                ]
            ])
            ->add(child: 'phoneNumber', type: TextType::class, options: [
                'label' => "Numéro de téléphone du client :",
                'attr' => [
                    'placeholder' => "Saisir le numéro de téléphone du client (Ex : 0614253678)",
                ]
            ])
            ->add(child: 'email', type: EmailType::class, options: [
                'label' => "Adresse électronique du client :",
                'attr' => [
                    'placeholder' => "Saisir l'adresse électronique du client",
                ]
            ])
            ->add('room', EntityType::class, [
                'label' => "La chambre à réserver",
                'class' => Room::class,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Booking::class,
        ]);
    }
}
