<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder

            ->add('email', TextType::class, [
                'required' => $options['required'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^((?:[\\p{L}0-9.!#$%&\'*+\\/=?^_`{|}~-]+)*@[\\p{L}0-9-._]+)$/ui',
                        'message'=>' Veuillez entrer une adresse email valide'
                    ])
                ]])

            ->add('password', TextType::class, [
                'required' => $options['required'],
                'label' => 'Mot de passe',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                new Regex([
                    'pattern' => '/(?=.*[A-Za-z])(?=.*[0-9]){8,}/',
                    'message'=>' Le mot de passe doit contenir
                    au moins 8 caractères 
                    dont un chiffre 
                    et une lettre'
                ]),
            ],
        ])
            ->add('firstname', TextType::class,
                ['label'=>'Prénom',
                    'required' => $options['required']])
            ->add('lastname', TextType::class,
                ['label'=>'Nom',
                    'required' => $options['required']])
            ->add('image', FileType::class, [
                'label' => 'Image',
                'mapped' => false,
                'required' => $options['required'],
                'constraints' => [
                    new File([
                        'maxSize' => '4096k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpeg',
                            'image/webp',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Votre image doit être au format : png, jpg, gif ou webp',
                    ])
                ],
            ])
            ->add('secteur', ChoiceType::class, [
                'choices' => [
                    'RH' => 'RH',
                    'Informatique' => 'Informatique',
                    'Comptabilité' => 'Comptabilité',
                    'Direction' => 'Direction',
                ],
                'placeholder' => 'Sélectionner un secteur',
                'required' => $options['required']
            ])
            ->add('contrat', ChoiceType::class, [
                'choices' => [
                    'CDI' => 'CDI',
                    'CDD' => 'CDD',
                    'Interim' => 'Interim',
                ],
                'placeholder' => 'Quel est le contrat',
                'required' => $options['required']
            ])
            ->add('sortie', DateType::class,
                [
                    'label' => 'Date de sortie',
                    'widget' => 'single_text',
                    'required' => false
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'csrf_protection'=>true,
        ]);
    }
}
