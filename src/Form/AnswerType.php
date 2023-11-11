<?php

namespace App\Form;

use App\Entity\Answer;
use App\Entity\Question;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Webmozart\Assert\Assert as AssertAssert;

class AnswerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', EntityType::class,[
                'class'         => Question::class,
                'choice_label'  => 'question',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'multiple'      => false,
                'mapped'        => true,
                'expanded'      => false,
                'constraints' => [
                       new Assert\NotBlank()
                ],
                 'attr' => [
                     'class' => 'form-select'
                 ]
            ])
            ->add('answer', TextType::class,[
                'attr' => [
                    'class' => 'form-control'
                ],
                'label' => 'Answer',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'constraints' => [
                    new Assert\NotBlank()
                ] 
            ])
            ->add('correct')
            -> add('submit', SubmitType::class, [
                'attr' => [
                'class' => 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Answer::class,
        ]);
    }
}
