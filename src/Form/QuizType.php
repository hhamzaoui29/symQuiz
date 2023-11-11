<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Question;
use App\Entity\Quiz;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuizType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        // attr == attribut
            ->add('level', TextType::class,[
                   'attr' => [
                    'class'=> 'form-control'
                   ],
                   'label' => 'Level',
                   'label_attr' => [
                    'class' => 'form-label mt-4'
                   ]

            ])

            ->add('title', TextType::class, [
            'attr' => [
                'class' => 'form-control'
            ],
            'label' => 'Title',
            'label_attr' => [
                'class' => 'form-label mt-4'
            ],
            ])
            ->add('submit', SubmitType::class,[
                'attr' => [
                    'class' => 'btn btn-primary mt-4' 
                ],
                'label' => 'Create'
            ])
            
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Quiz::class,
        ]);
    }
}
