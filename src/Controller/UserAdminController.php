<?php
namespace App\Controller;

use App\Entity\User;
use App\Form\UserEditType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Requirement\Requirement;
/**
 * Controller used to manage users.
 *
 * Please note that the application backend is developed manually for learning
 * purposes. However, in your real Symfony application you should use any of the
 * existing bundles that let you generate ready-to-use backends without effort.
 * See https://symfony.com/bundles
 *
 * @author gcadet
 */
#[Route('/useradmin')]
#[IsGranted('ROLE_ADMIN')]
final class UserAdminController extends AbstractController
{
    /**
     * Lists all User entities.
    **/

    #[Route('/list', name: 'userAdmin_list', methods: ['GET'])]
    public function list(
        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        // Récupérer la liste des utilisateurs depuis la table user
        $users = $entityManager->getRepository(User::class)->findAll();
        if (!$users) {
            throw $this->createNotFoundException('Aucun utilisateur trouvé.');
        }
        // Rendre la vue avec les informations de l'utilisateur
        return $this->render('utils/usersList.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/edit/{id}', name: 'userAdmin_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    public function edit(
        User $user, 
        Request $request, 
        EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $form = $this->createForm(UserEditType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur mis à jour avec succès.');
            return $this->redirectToRoute('userAdmin_list'); // Redirige vers la liste des utilisateurs
        }

        return $this->render('admin/userEdit_form.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }

    #[Route('/delete/{id}', name: 'userAdmin_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    public function delete(
        User $user, 
        EntityManagerInterface $entityManager, 
        Request $request): Response
    {
        // Vérifiez le token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete_user_' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
    
            $this->addFlash('success', 'Utilisateur supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression de l\'utilisateur.');
        }
    
        return $this->redirectToRoute('userAdmin_list');
    }
}