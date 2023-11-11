<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Form\AnswerType;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    //===============================//
    //======== Afficher Tous  =======//
    //===============================//
    //Création de la route
    #[Route('/answers', name: 'app.answers')]
    public function List(AnswerRepository $ar,
                        PaginatorInterface $paginator,
                        Request $req): Response
    {
        $answers = $paginator->paginate(
            $ar->findAll(),
            $req->query->getInt('page', 1),
            5
        );  
        return $this->render('answer/listAnswer.html.twig', [
            'answers' => $answers,
        ]);
    }
    //===============================//
    //======== Afficher One  =======//
    //===============================//
    //creation de la route
    #[route('answer/{id}', name: 'app.answer')]
    public function anAdress(AnswerRepository $ar, $id): Response
    {
        $answer = $ar->find($id);
        return $this->render('answer/anAdress.html.twig', [
            'answer' => $ar->find($id)
        ]);
    }
    //===============================//
    //======== Supprimer      =======//
    //===============================//
    //route pour la Suppression d'un animal
    #[Route('deleteAnswer/{id}', name: 'delete.answer')]
    public function delete(Answer $answer, EntityManagerInterface $emi, $id): Response
    {
        $emi->remove($answer);
        $emi->flush();
        $this->addFlash('success', ' Answer is deleted');
        return $this->redirectToRoute('app.answers');
    }
    //===============================//
    //======== Create&Update  =======//
    //===============================//
    //route pour Créer Un Nouveau animal
    #[Route('/createAnswer', name: 'create.answer')]
    #[Route('updateAnswer/{id}', name: 'update.answer')]
    public function createDeleteAnswer(
        Request $req,
        EntityManagerInterface $emi,
        AnswerRepository $ar,
        $id = null
    ): Response {
        //if the  does not exist we will create it
        if (!$id) {
            $answer = new Answer();
        } else {
            $answer = $ar->find($id);
        }

        //Creation of the Form
        $form = $this->createForm(AnswerType::class, $answer);
        $form->handleRequest($req);
        //Checking if the form has been submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            //we save in the database
            $emi->persist($answer);
            $emi->flush();

            //Message
            $this->addFlash('success', 'Action taken into account');
            return $this->redirectToRoute('app.answers');
        }
        return $this->render('answer/formAnswer.html.twig', [
            'answer' => $answer,
            'form'    => $form->createView()
        ]);
    }
}
