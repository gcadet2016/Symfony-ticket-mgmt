<?php
// src/Controller/underConstructionController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class underConstructionController extends AbstractController
{
    #[Route('/utils/underConstruction', name: 'under_construction')]
    public function index(): Response
    {
        return $this->render('utils/underConstruction.html.twig', [
        ]);
    }
}