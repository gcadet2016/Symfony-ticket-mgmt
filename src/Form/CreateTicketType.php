<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Status;
use App\Entity\Category;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;

class CreateTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Depending on the uer role, we will enable or disable certain fields.
        // For example, if the user is an admin, they can set the owner and status
        $isAdmin = $options['is_admin'] ?? false;
        if($isAdmin) {
            $isEditor = true; // Admins are considered as editors
        } else {
            $isEditor = $options['is_editor'] ?? false;
            $isUser = $options['is_user'] ?? false; // Assuming 'is_user' is passed for regular users
        }

        // Email: Admin and Users can edit, Editors can only view
        if($isAdmin || $isUser) {
            $builder->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'placeholder' => 'Saisir votre email',
                    'class' => 'input-control',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'L\'email ne peut pas être vide.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Email([
                        'message' => 'Veuillez saisir un email valide.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 64,
                        'maxMessage' => 'L\'email ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
        } else {
            $builder->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'placeholder' => 'Saisir votre email',
                    'class' => 'input-control',
                    'readonly' => true, // Disable the field for editors
                ],
            ]);
        }
        // Creation date: Admin can edit, Editors and Users can only view
        if($isEditor || $isUser) {
            $builder->add('creationDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création',
                'required' => true,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'class' => 'input-control',
                    'placeholder' => 'Sélectionnez la date de création',
                    'readonly' => true,
                ],
            ]);
        } else {
            $builder->add('creationDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création',
                'required' => true,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'class' => 'input-control',
                    'placeholder' => 'Sélectionnez la date de création',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'La date de création ne peut pas être vide.',
                    ]),
                ],
            ]);
        }
        // Close date: Admin can edit, Editors and Users can only view
        if($isEditor || $isUser) {
            $builder->add('closeDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de clôture',
                'required' => false,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'class' => 'input-control',
                    'placeholder' => 'Sélectionnez la date de clôture (optionnelle)',
                    'readonly' => true,
                ],
            ]);
        } else {
            $builder->add('closeDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de clôture',
                'required' => false,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'class' => 'input-control',
                    'placeholder' => 'Sélectionnez la date de clôture (optionnelle)',
                ],
            ]);
        }
        // Description: Admin and Users can edit, Editors can only view
        if($isAdmin || $isUser) {
            $builder->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'rows' => 5,
                    'cols' => 50,
                    'maxlength' => 512,
                    'class' => 'input-control',
                    'placeholder' => 'Enter ticket description here...'
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'La description ne peut pas être vide.',
                    ]),
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 512,
                        'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
        } else {
            $builder->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'rows' => 5,
                    'cols' => 50,
                    'maxlength' => 512,
                    'class' => 'input-control',
                    'placeholder' => 'Enter ticket description here...',
                    'readonly' => true, // Disable the field for editors
                ],
            ]);
        }

        // Category: Admin and Users can edit, Editor can only view
        if($isAdmin || $isUser) {
            $builder->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ à afficher
                'label' => 'Categorie',
                'required' => true,
                'placeholder' => 'Sélectionnez une catégorie',
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'La catégorie ne peut pas être vide.',
                    ]),
                ],
            ]);
        } else {
            $builder->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ à afficher
                'label' => 'Categorie',
                'required' => true,
                'placeholder' => 'Sélectionnez une catégorie',
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'disabled' => true, // Disable the field for non-admin
                ],
            ]);
        };

        // Owner: Admin are allowed to set the owner
        if($isAdmin) {
            $builder->add('owner', TextType::class, [
                'label' => 'Assigné à',
                'required' => false,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'placeholder' => 'Pris en charge par',
                    'class' => 'input-control',
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\Length([
                        'max' => 64,
                        'maxMessage' => 'Le nom de l\'assigné ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ]);
        } else {
            $builder->add('owner', TextType::class, [
                'label' => 'Assigné à',
                'required' => false,
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'placeholder' => 'Pris en charge par',
                    'class' => 'input-control',
                    'readonly' => true,
                ],
            ]);
        }

        // Status: Editors and Admin are allowed to set the status
        if($isEditor || $isAdmin) {
            $builder->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ à afficher
                'label' => 'Statut',
                'required' => true,
                'placeholder' => 'Sélectionnez un statut',
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'constraints' => [
                    new \Symfony\Component\Validator\Constraints\NotBlank([
                        'message' => 'Le statut ne peut pas être vide.',
                    ]),
                ],
            ]);
        } else {
            $builder->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ à afficher
                'label' => 'Statut',
                'required' => true,
                'placeholder' => 'Sélectionnez un statut',
                'label_attr' => [
                    'class' => 'input-label', // Ajoutez ici vos classes CSS pour le label
                ],
                'attr' => [
                    'disabled' => true, // Disable the field for non-editors
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
            'is_admin' => false,
            'is_editor' => false,
            'is_user' => false, 
        ]);
    }
}
?>