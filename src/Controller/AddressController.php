<?php

namespace App\Controller;

use App\Entity\Address;
use App\Form\AddressType;
use App\Repository\AddressRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AddressController extends AbstractController
{
    //===============================//
    //======== Afficher Tous  =======//
    //===============================//
    //Création de la route
    #[Route('/addresses', name: 'app_addresses')]
    public function addressList(AddressRepository $ar): Response
    {
        return $this->render('address/listAddress.html.twig', [
            'addresses' => $ar->findAll(),
        ]);
    }
    //===============================//
    //======== Afficher One  =======//
    //===============================//
    //creation de la route
    #[route('adress/{id}', name: 'app_address')]
    public function anAdress(AddressRepository $ar, $id): Response
    {
        $address = $ar->find($id);
        return $this->render('address/anAdress.html.twig', [
            'adress' => $address
        ]);
    }
    //===============================//
    //======== Supprimer      =======//
    //===============================//
    //route pour la Suppression d'un animal
    #[Route('deleteAddress/{id}', name: 'delete_address')]
    public function deleteAddress(Address $address, EntityManagerInterface $emi, $id): Response
    {
        $emi->remove($address);
        $emi->flush();
        $this->addFlash('success', 'address is deleted');
        return $this->redirectToRoute('app_addresses');
    }
    //===============================//
    //======== Create&Update  =======//
    //===============================//
    //route pour Créer Un Nouveau animal
    #[Route('/createAddress', name: 'create_address')]
    #[Route('updateAddress/{id}', name: 'update_address')]
    public function createDeleteAddress(
        Request $req,
        EntityManagerInterface $emi,
        AddressRepository $ar,
        $id = null ) : Response
    {
        //if the address does not exist we will create it
        if (!$id) {
            $address = new Address();
        } else {
            $address = $ar->find($id);
        }

        //Creation of the Form
        $form = $this->createForm(AddressType::class, $address);
        $form->handleRequest($req);
        //Checking if the form has been submitted and is valid
        if ($form->isSubmitted() && $form->isValid()) {
            //we save in the database
            $emi->persist($address);
            $emi->flush();

            //Message
            $this->addFlash('success', 'Action taken into account');
            return $this->redirectToRoute('app_addresses');
        }
        return $this->render('address/formAddress.html.twig', [
            'address' => $address,
            'form'    => $form->createView()
        ]);
    }
}
