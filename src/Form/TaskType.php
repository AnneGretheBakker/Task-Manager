<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class TaskType extends AbstractType
{
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
