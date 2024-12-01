<?php
namespace App\Controller;

use App\Entity\Genre;
use App\Form\GenreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GenreController extends AbstractController
{
    #[Route('/genre', name: 'app_genre')]
    public function index(): Response
    {
        return $this->render('genre/index.html.twig', [
            'controller_name' => 'GenreController',
        ]);
    }

    #[Route('/genre/ajout', name: 'app_genre_ajout')]
    public function ajout(Request $request, EntityManagerInterface $entityManager): Response
    {
        $genre = new Genre();
        $form = $this->createForm(GenreType::class, $genre);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($genre);
            $entityManager->flush();

            $this->addFlash('success', 'Genre ajouté avec succès !');
            return $this->redirectToRoute('app_genre');
        }

        return $this->render('genre/ajout.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
