<?php

// src/Controller/HomeController.php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Response;

final class HomeController extends AbstractController 
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('app/home.html.twig', [
        ]);
    }
}
?>