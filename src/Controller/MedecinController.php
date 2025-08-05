<?php

namespace App\Controller;

use App\Entity\Medecin;
use App\Form\MedecinType;
use App\Repository\MedecinRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MedecinController extends AbstractController
{
    #[Route('/medecin', name: 'app_medecin')]
    public function index(MedecinRepository $medecinRepository): Response
    {
        $medecin = $medecinRepository->findAll();
        return $this->render('medecin/index.html.twig', [
            'medecin' => $medecin,
        ]);
    }
    
    #[Route('/medecin/{id}/edit', name: 'medecin.edit')]
    public function edit(Medecin $medecin, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            return $this->redirectToRoute('app_medecin');
        }

        return $this->render('medecin/edit.html.twig', [
            'form' => $form->createVi(),
            'medecin' => $medecin,
        ]);
    }

    #[Route('/medecin/add', name: 'medecin.add')]
    public function add(Request $request, EntityManagerInterface $entityManager): Response
    {
        $medecin = new Medecin();
        $form = $this->createForm(MedecinType::class, $medecin);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($medecin);
            $entityManager->flush();
            $this->addFlash('success', 'Médecin enregistré avec succès');
            return $this->redirectToRoute('app_medecin');
        }

        return $this->render('medecin/add.html.twig', [
            'form' => $form,
            'medecin' => $medecin,
        ]);
    }

    #[Route('/medecin/{id}/delete', name: 'medecin.delete')]
    public function delete(Medecin $medecin, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($medecin);
        $entityManager->flush();
        
        $this->addFlash('success', 'Médecin supprimé avec succès');

        return $this->redirectToRoute('app_medecin');
    }
    
    #[Route('/medecin/read', name: 'medecin.read')]
    public function read(EntityManagerInterface $entityManager): Response
    {
        $medecins = $entityManager->getRepository(Medecin::class)->findAll();

        return $this->render('medecin/read.html.twig', [
            'medecins' => $medecins,
        ]);
    }
}
