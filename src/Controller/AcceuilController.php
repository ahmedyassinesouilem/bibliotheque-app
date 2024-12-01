<?php
namespace App\Controller;

use App\Entity\Livre;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    #[Route('/acceuil', name: 'app_acceuil')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $livres = $entityManager->getRepository(Livre::class)->findAll();

        return $this->render('acceuil/index.html.twig', [
            'livres' => $livres,
        ]);
    }

    #[Route('/livre/{id}', name: 'app_livre_details', requirements: ['id' => '\d+'])]
    public function details(int $id, EntityManagerInterface $entityManager): Response
    {
        $livre = $entityManager->getRepository(Livre::class)->find($id);

        if (!$livre) {
            throw $this->createNotFoundException('Livre non trouvé.');
        }

        return $this->render('livre/details.html.twig', [
            'livre' => $livre,
        ]);
    }
}
