<?php
namespace App\Controller;

use App\Entity\Auteur;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuteurController extends AbstractController
{
    #[Route('/auteur', name: 'app_auteur')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les auteurs
        $auteurs = $entityManager->getRepository(Auteur::class)->findAll();

        return $this->render('auteur/index.html.twig', [
            'auteurs' => $auteurs,
        ]);
    }

    #[Route('/auteur/ajout', name: 'app_auteur_ajout')]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $auteur = new Auteur();
        $form = $this->createForm(AuteurType::class, $auteur);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($auteur);
            $entityManager->flush();

            $this->addFlash('success', 'Auteur ajouté avec succès !');
            return $this->redirectToRoute('app_auteur');
        }

        return $this->render('auteur/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
