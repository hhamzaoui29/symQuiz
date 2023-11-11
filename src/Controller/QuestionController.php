<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    //===============================//
    //======== Afficher Tous  =======//
    //===============================//
    //Création de la route
    #[Route('/questions', name: 'app_questions')]
    public function ListQuestion(QuestionRepository $ar): Response
    {
        return $this->render('question/listQuestion.html.twig', [
            'questions' => $ar->findAll(),
        ]);
    }
    //===============================//
    //======== Afficher One  =======//
    //===============================//
    //creation de la route
    #[route('aQuestion/{id}', name: 'aQuestion')]
    public function anAdress(QuestionRepository $ar, $id): Response
    {
        $question = $ar->find($id);
        return $this->render('question/anAdress.html.twig', [
            'question' => $question
        ]);
    }
    //===============================//
    //======== Supprimer      =======//
    //===============================//
    //route pour la Suppression d'un animal
    #[Route('deleteQuestion/{id}', name: 'delete_question')]
    public function delete(Question $question, EntityManagerInterface $emi, $id): Response
    {
        $emi->remove($question);
        $emi->flush();
        $this->addFlash('success', 'Question is deleted');
        return $this->redirectToRoute('app_questions');
    }
    //===============================//
    //======== Create&Update  =======//
    //===============================//
    //route pour Créer Un Nouveau animal
    #[Route('/createQuestion', name: 'create_question')]
    #[Route('updateQuestion/{id}', name: 'update_question')]
    public function createDelete(
        Request $req,
        EntityManagerInterface $emi,
        QuestionRepository $ar,
        $id = null
    ): Response {
        //if the  does not exist we will create it
        if (!$id) {
            $question = new Question();
        } else {
            $question = $ar->find($id);
        }

        //Creation of the Form
        $form = $this->createForm(QuestionType::class, $question);
        $form->handleRequest($req);
        //Checking if the form has been submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            //we save in the database
            $emi->persist($question);
            $emi->flush();

            //Message
            $this->addFlash('success', 'Action taken into account');
            return $this->redirectToRoute('app_questions');
        }
        return $this->render('question/formQuestion.html.twig', [
            'question' => $question,
            'form'    => $form->createView()
        ]);
    }
}
