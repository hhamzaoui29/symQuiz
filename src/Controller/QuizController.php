<?php

namespace App\Controller;


use App\Entity\Quiz;
use App\Form\QuizType;

use App\Repository\QuizRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Form\FormFactoryInterface;

class QuizController extends AbstractController
{
//===============================//
//======== Afficher Tous  =======//
//===============================//
//Création de la route
    #[Route('/quizs', name: 'app.quizs')]
    public function List(QuizRepository $qr,
                          Request $req,
                          PaginatorInterface $paginator): Response
    {
        $quizs = $paginator->paginate(
            $qr->findAll(),
            $req->query->getInt('page', 1),
            5
        );   
        return $this->render('quiz/listQuiz.html.twig', [
            'quizs' => $quizs,
        ]);
    }

//===============================//
//======== Afficher One  =======//
//===============================//
//creation de la route
    #[route('/quiz/{id}', name: 'aQuiz')]
    public function anAdress(QuizRepository $qr, $id): Response
    {
        $quiz = $qr->find($id);
        return $this->render('quiz/quiz.html.twig', [
            'quiz' => $quiz
        ]);
    }

//===============================//
//======== Supprimer      =======//
//===============================//
//route pour la Suppression d'un animal
    #[Route('deleteQuiz/{id}', name: 'delete.quiz')]
    public function delete(Quiz $quiz, EntityManagerInterface $emi, $id): Response
    {
        $emi->remove($quiz);
        $emi->flush();
        $this->addFlash('success', 'Quiz is deleted');
        return $this->redirectToRoute('app.quizs');
    }

//===============================//
//======== Create&Update  =======//
//===============================//
//route pour Créer Un Nouveau animal
    #[Route('createQuiz', name: 'create.quiz')]
    #[Route('updateQuiz/{id}', name: 'update.quiz')]
    public function createDeleteQuiz(
        Request $req,
        EntityManagerInterface $emi,
        QuizRepository $qr,
        $id = null
    ): Response {
        //if the  does not exist we will create it
        if (!$id) {
            $quiz = new Quiz();
        } else {
            $quiz = $qr->find($id);
        }

        //Creation of the Form
        $form = $this->createForm(QuizType::class, $quiz);
        $form->handleRequest($req);
        //Checking if the form has been submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            //we save in the database
            $emi->persist($quiz);
            $emi->flush();

            //Message
            $this->addFlash('success', 'Action taken into account');
            return $this->redirectToRoute('app.quizs');
        }
        return $this->render('quiz/formCreateQuiz.html.twig', [
            'quiz' => $quiz,
            'form' => $form->createView()
        ]);
    }
//===============================//
//======  getQuizById =======//
//===============================//
//
    #[Route("testQuiz/{id}", name:"test.quiz")]
    public function quiz(QuizRepository $qr, $id = null) : Response
    {
        // on rentre d'abord dans le formulaire quiz.html
        $results = $qr->getQuizById($id);
        $level = $qr->find($id);
        // dd($level);
        return $this->render(('quiz/quiz.html.twig'),[
            'quizs' =>$results, 
            'level' => $level
        ]);  
    }  
}





