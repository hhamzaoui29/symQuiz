<?php

namespace App\Form;

use App\Entity\Question;
use App\Entity\Quiz;
use App\Repository\QuizRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('question', TextType::class,[
                   'attr' => [
                    'class'=> 'form-control'
                   ],
                   'label' => 'Question',
                   'label_attr' => [
                    'class' => 'form-label mt-4'
                   ],
                   'constraints' => [
                       new Assert\NotBlank()
                   ]
            ])
            ->add('quiz', EntityType::class,[
                'class'        => Quiz::class,
                'query_builder'=> function(QuizRepository $qr){
                    return $qr->createQueryBuilder('i')
                    ->orderBy('i.title','ASC');
                },
                'choice_label' => 'title',
                'label'        => 'level of quiz',
                'label_attr' => [
                    'class' => 'form-label mt-4'
                ],
                'multiple'     => false,
                'mapped'       => true,
                'expanded'     => false,
                'attr'=>[
                    'class'=> 'form-select'
                ]
            ])
            ->add('submit', SubmitType::class,[
                'attr'=>[
                    'class'=> 'btn btn-primary mt-4'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
