<?php

namespace App\Controller;

use App\Entity\RendezVous;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RendezVousController extends AbstractController
{
    #[Route('/rendezvous', name: 'app_rendezvous')]
    public function index(RendezVousRepository $rendezvousRepository): Response
    {
        $rendezvous =$rendezvousRepository->findAll();
        return $this->render('rendezvous/index.html.twig', [
            'rendezvous' => $rendezvous,
        ]);
    }
    #[Route('/rendezvous/{id}/edit', name: 'rendezvous.edit')]
    public function edit(RendezVous $rendezvous, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(RendezVousType::class,$rendezvous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_rendezvous');
        }

        return $this->render('rendezvous/edit.html.twig', [
            'form' => $form,
            'rendezvous' => $rendezvous,
        ]);
    }

    #[Route('/rendezvous/add', name: 'rendezvous.add')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $rendezvous = new RendezVous();
        $form = $this->createForm(RendezVousType::class,$rendezvous);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManagerInterface->persist($rendezvous);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'RendezVous enregistré avec succès');
            return $this->redirectToRoute('app_rendezvous');
        }

        return $this->render('rendezvous/add.html.twig', [
            'form' => $form,
            'rendezvous' => $rendezvous,
        ]);
    }

    #[Route('/rendezvous/{id}/delete', name: 'rendezvous.delete')]
    public function delete(RendezVous $rendezvous, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($rendezvous);
        $entityManager->flush();
        
        $this->addFlash('success', 'RenderVous supprimé avec succès');

        return $this->redirectToRoute('app_rendezvous'); // Remplacez 'app_cours' par le nom de la route vers la liste des cours.
    }
    
    #[Route('/rendezvous/read', name: 'rendezvous.read')]
    public function read(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupérer la liste de tous les patients depuis la base de données
        $rendezvouss = $entityManagerInterface->getRepository(RendezVous::class)->findAll();

        // Retourner la vue Twig avec la liste des patients
        return $this->render('rendezvous/read.html.twig', [
            
            'rendezvouss' => $rendezvouss,
        ]);
    }

}

