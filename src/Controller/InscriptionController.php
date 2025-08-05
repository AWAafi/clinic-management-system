<?php

namespace App\Controller;

use App\Entity\Utilisateur;
use App\Form\InscriptionType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class InscriptionController extends AbstractController
{
    #[Route('/inscription', name: 'app_inscription')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur =$utilisateurRepository->findAll();
        return $this->render('inscription/index.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
   
    #[Route('/inscription/add', name: 'inscription.add')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $utilisateur = new Utilisateur();
        $form = $this->createForm(InscriptionType::class,$utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManagerInterface->persist($utilisateur);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'utilisateur enregistré avec succès');
            return $this->redirectToRoute('app_inscription');
        }

        return $this->render('inscription/add.html.twig', [
            'form' => $form,
            'utilisateur' => $utilisateur,
        ]);

    }
    #[Route('/inscription/{id}/delete', name: 'inscription.delete')]
    public function delete(Utilisateur $utilisateur, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($utilisateur);
        $entityManager->flush();
        
        $this->addFlash('success', 'Annuler avec succès');

        return $this->redirectToRoute('app_inscription'); // Remplacez 'app_cours' par le nom de la route vers la liste des cours.

    }
    #[Route('/inscription/{id}/edit', name: 'inscription.edit')]
    public function edit(Utilisateur $utilisateur, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(InscriptionType::class,$utilisateur);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_inscription');
        }

        return $this->render('/inscription/edit.html.twig', [
            'form' => $form,
            'utilisateur' => $utilisateur,
        ]);
    }
    #[Route('/inscription/read', name: 'inscription.read')]
    public function read(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupérer la liste de tous les utilisateurs depuis la base de données
        $utilisateurs = $entityManagerInterface->getRepository(Utilisateur::class)->findAll();

        // Retourner la vue Twig avec la liste des utilisateurs
        return $this->render('inscription/read.html.twig', [
            
            'utilisateurs' => $utilisateurs,
        ]);
    }

}
