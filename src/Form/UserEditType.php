<?php

// Used to edit user (form)
// UserEditController.php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class UserEditType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => ['autofocus' => true, 'class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label' => 'Email',
            ])
            ->add('userName', TextType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'help' => 'Uniquement utilisé pour l\'affichage',
                'label' => 'Nom d\'utilisateur',
            ])
            ->add('roles', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label' => 'Rôles',
                'choices' => [
                    'Utilisateur' => 'ROLE_USER',
                    'Administrateur' => 'ROLE_ADMIN',
                    'Personnel agence' => 'ROLE_EDITOR',
                ],
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('isVerified', ChoiceType::class, [
                'attr' => ['class' => 'form-control'],
                'row_attr' => ['class' => 'form-group'],
                'label' => 'Email vérifié',
                'choices' => [
                    'Oui' => true,
                    'Non' => false,
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}