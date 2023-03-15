<?php

namespace App\Form\Admin;

use App\Entity\Admin;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;

class AdminType extends AbstractType
{
    private Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(child: 'email', type: EmailType::class, options: [
                'label' => "Adresse électronique de l'administrateur :",
                'attr' => [
                    'placeholder' => "Saisir l'adresse électronique de l'administrateur",
                ]
            ])
            ->add(child: 'username', type: TextType::class, options: [
                'label' => "Pseudo de l'administrateur :",
                'attr' => [
                    'placeholder' => "Saisir le pseudo de l'administrateur",
                ]
            ])
            ->add(child: 'lastName', type: TextType::class, options: [
                'label' => "Nom de l'administrateur :",
                'attr' => [
                    'placeholder' => "Saisir le nom de l'administrateur",
                ]
            ])
            ->add(child: 'firstName', type: TextType::class, options: [
                'label' => "Prénom de l'administrateur :",
                'attr' => [
                    'placeholder' => "Saisir le prénom de l'administrateur",
                ]
            ])
            ->add(child: 'gender', type: ChoiceType::class, options: [
                'label' => "Civilité de l'administrateur :",
                'choices' => [
                    'Homme' => 'm',
                    'Femme' => 'f',
                ],
                'multiple' => false,
                'expanded' => true,
            ])
            ->add(child: 'roles', type: ChoiceType::class, options: [
                'label' => "Statut de l'administrateur :",
                'choices' => [
                    'Administrateur' => 'ROLE_ADMIN',
                    'Superadministrateur' => 'ROLE_SUPER_ADMIN',
                ],
                'expanded' => true,
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe :',
                    'attr' => [
                        'placeholder' => "Saisir le mot de passe",
                    ]
                ],
                'second_options' => [
                    'label' => 'Confirmation du mot de passe :',
                    'attr' => [
                        'placeholder' => "Confirmer le mot de passe",
                    ]
                ],
                'invalid_message' => 'Les mots de passe ne sont pas identiques.',
                'required' => in_array('password', $options['validation_groups'] ?? []),
            ]);

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    return [$rolesString];
                }
            ));

        if (!$this->security->isGranted('ROLE_SUPER_ADMIN')) {
            $builder->remove('roles');
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Admin::class,
        ]);
    }
}
