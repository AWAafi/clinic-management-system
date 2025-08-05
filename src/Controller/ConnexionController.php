<?php

namespace App\Controller;

use App\Form\LoginType;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\UtilisateurRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;



class ConnexionController extends AbstractController
{
    #[Route('/connexion', name: 'app_connexion')]
    public function index(UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur =$utilisateurRepository->findAll();
        return $this->render('connexion/index.html.twig', [
            'utilisateur' => $utilisateur,
        ]);
    }
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route('/connexion/login', name: 'connexion.login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, EntityManagerInterface $entityManagerInterface): Response
    {
        $utilisateur = new Utilisateur();

        // Créer le formulaire de connexion
        $form = $this->createForm(LoginType::class, $utilisateur);

        // Gérer la soumission du formulaire
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Récupérer les données soumises par le formulaire
            $formData = $form->getData();

            // Accéder aux propriétés de l'objet Utilisateur
            $email = $formData->getEmail();
            $password = $formData->getPassword();

            try {
                // Rechercher l'utilisateur correspondant dans la base de données
                $utilisateur = $this->entityManager->getRepository(Utilisateur::class)->findOneByEmail($email);

                // Vérifier le mot de passe
                if ($utilisateur && password_verify($password, $utilisateur->getPassword())) {
                    // Connexion réussie
                    return $this->redirectToRoute('app_connexion');
                } else {
                    // Mot de passe incorrect
                    throw new BadCredentialsException('Email ou mot de passe incorrect.'); // Changement ici
                }
            } catch (BadCredentialsException $e) { // Changement ici
                // Afficher un message d'erreur
                $this->addFlash('error', $e->getMessage());
            }
        }

        // Afficher le formulaire de connexion avec les erreurs éventuelles
        return $this->render('connexion/login.html.twig', [
            'form' => $form,
            'utilisateur' => $utilisateur,
            'error' => $error ?? null,
        ]);
    }
    
        
}
