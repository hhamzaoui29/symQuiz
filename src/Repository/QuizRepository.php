<?php

namespace App\Repository;
//cd Documents/projet-quiz/quiz1.1
use App\Entity\Quiz;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Quiz>
 *
 * @method Quiz|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quiz|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quiz[]    findAll()
 * @method Quiz[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuizRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quiz::class);
    }

    public function getQuizById($quizId)
    {
        $entityManager = $this->getEntityManager();
        $query = $entityManager->createQuery('
        SELECT 
            question.id as question_id,
            question.question as question_text,
            answer.id as answer_id,
            answer.answer as answer_text,
            answer.correct as is_correct
        FROM App\Entity\Quiz quiz
        INNER JOIN quiz.questions question
        INNER JOIN question.answers answer
        WHERE quiz.id = :quizId
    ')
            ->setParameter('quizId', $quizId);

        $results = $query->getResult();

        $formattedResults = [];

        foreach ($results as $result) {
            $questionId = $result['question_id'];

            if (!isset($formattedResults[$questionId])) {
                $formattedResults[$questionId] = [
                    'question' => $result['question_text'],
                    'answers' => [],
                ];
            }

            // Vérifiez si la réponse n'a pas déjà été ajoutée
            $answerText = $result['answer_text'];
            $isCorrect = $result['is_correct'];

            if (!in_array(['text' => $answerText, 'correct' => $isCorrect], 
                            $formattedResults[$questionId]['answers'], true)) 
                            {
                             $formattedResults[$questionId]['answers'][] = 
                             [
                              'text' => $answerText,
                              'correct' => $isCorrect,
                             ];
                            }
        }

        return $formattedResults;
    }
}
