<?php
// src/Controller/phpInfoController.php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class phpInfoController extends AbstractController
{
    #[Route('/utils/phpinfo', name: 'app_phpinfo')]
    public function index(): Response
    {
        return $this->render('utils/phpinfo.html.twig', [
            'controller_name' => 'phpInfoController',
            'phpinfo' => phpinfo(),
        ]);
    }
}
