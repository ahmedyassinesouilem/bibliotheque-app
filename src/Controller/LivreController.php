<?php
namespace App\Controller;

use App\Entity\Livre;
use App\Form\LivreType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class LivreController extends AbstractController
{
    // Action pour afficher la liste des livres
    #[Route('/livres', name: 'lister_livres')]
    public function listerLivres(EntityManagerInterface $entityManager): Response
    {
        // Récupérer tous les livres
        $livres = $entityManager->getRepository(Livre::class)->findAll();

        // Passer les livres à la vue
        return $this->render('livre/lister.html.twig', [
            'livres' => $livres,
        ]);
    }

    // Action pour ajouter un livre
    #[Route('/livre/ajouter', name: 'ajouter_livre')]
    public function ajouterLivre(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $livre = new Livre();
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('ajouter_livre');
                }

                // Enregistre le nom du fichier dans l'entité
                $livre->setImage($newFilename);
            }

            $entityManager->persist($livre);
            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été ajouté avec succès !');
            return $this->redirectToRoute('app_acceuil');
        }

        return $this->render('livre/ajouter.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    // Action pour modifier un livre
    #[Route('/livre/modifier/{id}', name: 'modifier_livre')]
    public function modifierLivre(Livre $livre, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(LivreType::class, $livre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('uploads_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Une erreur est survenue lors du téléchargement de l\'image.');
                    return $this->redirectToRoute('modifier_livre', ['id' => $livre->getId()]);
                }

                // Enregistre le nouveau nom de fichier
                $livre->setImage($newFilename);
            }

            $entityManager->flush();

            $this->addFlash('success', 'Le livre a été modifié avec succès !');
            return $this->redirectToRoute('lister_livres');
        }

        return $this->render('livre/modifier.html.twig', [
            'form' => $form->createView(),
            'livre' => $livre,
        ]);
    }

    // Action pour supprimer un livre
    #[Route('/livre/supprimer/{id}', name: 'supprimer_livre')]
    public function supprimerLivre(Livre $livre, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($livre);
        $entityManager->flush();

        $this->addFlash('success', 'Le livre a été supprimé avec succès !');
        return $this->redirectToRoute('lister_livres');
    }
}
