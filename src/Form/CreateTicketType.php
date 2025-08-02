<?php

namespace App\Form;

use App\Entity\Ticket;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Status;
use App\Entity\Category;

class CreateTicketType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'required' => true,
                'attr' => [
                    'placeholder' => 'Saisir votre email',
                    'class' => 'form-control',
                ],
            ])
            ->add('creationDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de création',
                'required' => true,
            ])
            ->add('closeDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date de clôture',
                'required' => false,
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'required' => true,
                'attr' => [
                    'rows' => 5,
                    'cols' => 50,
                    'maxlength' => 512,
                    'class' => 'form-control',
                    'placeholder' => 'Enter ticket description here...'
                ],
            ])
            ->add('category', EntityType::class, [
                'class' => Category::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ à afficher
                'label' => 'Categorie',
                'required' => true,
                'placeholder' => 'Sélectionnez une catégorie',
            ])
            ->add('status', EntityType::class, [
                'class' => Status::class,
                'choice_label' => 'name', // Remplacez 'name' par le champ à afficher
                'label' => 'Statut',
                'required' => true,
                'placeholder' => 'Sélectionnez un statut',
            ])
            ->add('owner', TextType::class, [
                'label' => 'Assigné à',
                'required' => false,
                'attr' => [
                    'placeholder' => 'Pris en charge par',
                    'class' => 'form-control',
                ],
            ]);
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
