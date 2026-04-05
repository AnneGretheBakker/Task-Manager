<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

/**
 * Type for creating and editing Task Entities
 *
 * Defines the form fields for Task: title, description, deadline, status and priority
 *
 * @extends AbstractType
 */
class TaskType extends AbstractType
{
    /**
     * Builds the Task Form with the necessary fields
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The additional options (not used yet)
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('deadline')
            ->add('status', ChoiceType::class, [
                'choices' => [
                    'Open' => 'Open',
                    'Doing' => 'Doing',
                    'Done' => 'Done',
                ],
                'attr' => ['class' => 'form-control'],
            ])
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Low' => 'Low',
                    'Medium' => 'Medium',
                    'High' => 'High',
                ],
                'attr' => ['class' => 'form-control'],
            ])
        ;
    }

    /**
     * Configures default options for this form type
     *
     * @param OptionsResolver $resolver The options resolver
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
