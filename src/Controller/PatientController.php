<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Form\PatientType;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class PatientController extends AbstractController
{
    #[Route('/patient', name: 'app_patient')]
    public function index(PatientRepository $patientRepository): Response
    {
        $patient =$patientRepository->findAll();
        return $this->render('patient/index.html.twig', [
            'patient' => $patient,
        ]);
    }
    #[Route('/patient/{id}/edit', name: 'patient.edit')]
    public function edit(Patient $patient, Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $form = $this->createForm(PatientType::class,$patient);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManagerInterface->flush();
            return $this->redirectToRoute('app_patient');
        }

        return $this->render('patient/edit.html.twig', [
            'form' => $form,
            'patient' => $patient,
        ]);
    }

    #[Route('/patient/add', name: 'patient.add')]
    public function add(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class,$patient);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid())
        {
            $entityManagerInterface->persist($patient);
            $entityManagerInterface->flush();
            $this->addFlash('success', 'Patient enregistré avec succès');
            return $this->redirectToRoute('app_patient');
        }

        return $this->render('patient/add.html.twig', [
            'form' => $form,
            'patient' => $patient,
        ]);
    }

    #[Route('/patient/{id}/delete', name: 'patient.delete')]
    public function delete(Patient $patient, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($patient);
        $entityManager->flush();
        
        $this->addFlash('success', 'patient supprimé avec succès');

        return $this->redirectToRoute('app_patient'); // Remplacez 'app_cours' par le nom de la route vers la liste des cours.
    }
    
    #[Route('/patient/read', name: 'patient.read')]
    public function read(Request $request, EntityManagerInterface $entityManagerInterface): Response
    {
        // Récupérer la liste de tous les patients depuis la base de données
        $patients = $entityManagerInterface->getRepository(Patient::class)->findAll();

        // Retourner la vue Twig avec la liste des patients
        return $this->render('patient/read.html.twig', [
            
            'patients' => $patients,
        ]);
    }

}
