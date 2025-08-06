<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Ticket;
use App\Form\CreateTicketType;
use App\Form\EditTicketType;
use App\Entity\Status;
use App\Entity\Category;
use Psr\Log\LoggerInterface;

final class TicketController extends AbstractController
{
    // #[Route('/ticket', name: 'app_ticket')]
    // public function index(): Response
    // {
    //     return $this->render('ticket/index.html.twig', [
    //         'controller_name' => 'TicketController',
    //     ]);
    // }

    public function __construct(
        private LoggerInterface $logger, // Injection du logger
    )
    {
        // Le logger est injecté via le constructeur
        // Il peut être utilisé pour enregistrer des messages de log
        $this->logger->debug('TicketController initialized');

    }

    #[Route('/ticket', name: 'app_tickets_list', methods: ['GET'])]
    public function list(
        EntityManagerInterface $entityManager
    ): Response {
        // If the user is not authenticated, they will be redirected to the login page.
        // This is a security measure to ensure that only authenticated users can access their own ticket list.
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        // Retrieve the tickets from the database
        // If the user has the ROLE_EDITOR, they can see all tickets.
        // Otherwise, they can only see their own tickets based on their email.
        if ($this->isGranted('ROLE_EDITOR')) {
            $tickets = $entityManager->getRepository(Ticket::class)->findAll();
        } else {
            $userEmail = $this->getUser()->getEmail();

            $tickets = $entityManager->getRepository(Ticket::class)
                ->findBy(['email' => $userEmail]);
        }
        return $this->render('app/listTickets.html.twig', [
            'tickets' => $tickets,
        ]);
    }

    #[Route('/ticket/create', name: 'app_ticket_create', methods: ['GET', 'POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        // If the user is not authenticated, they will be redirected to the login page.
        // This is a security measure to ensure that only authenticated users can create a ticket.
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Create a new Ticket entity
        // The creation date is set to the current date and time in the 'Europe/Paris' timezone.
        // The status is set by default to 'Nouveau' (New) and the category is set by default to 'Incident'.
        // These are default values that can be changed later by the user
        $ticket = new Ticket();
        $ticket->setEmail($this->getUser()->getEmail());
        $ticket->setCreationDate(new \DateTime('now', new \DateTimeZone('Europe/Paris'))); // Définit la date à aujourd'hui
        $statusRepo = $entityManager->getRepository(Status::class);
        $statusNouveau = $statusRepo->findOneBy(['name' => 'Nouveau']);
        $ticket->setStatus($statusNouveau); // Set default status to 'Nouveau'
        $categoryRepo = $entityManager->getRepository(Category::class);
        $categoryIncident = $categoryRepo->findOneBy(['name' => 'Incident']);
        $ticket->setCategory($categoryIncident); // Set default category to 'Incident'

        $form = $this->createForm(CreateTicketType::class, $ticket, [
            'is_admin' => $this->isGranted('ROLE_ADMIN'),
            'is_editor' => $this->isGranted('ROLE_EDITOR'),
            'is_user' => $this->isGranted('ROLE_USER'),
        ]);

        $form->handleRequest($request);

        if($ticket->getStatus() === null) {
            // The status is null because user cannot set it (disabled in form), set it to 'Nouveau' (New)
            $this->logger->debug('Status is null, setting default status to Nouveau');
            $statusNouveau = $statusRepo->findOneBy(['name' => 'Nouveau']);
            $ticket->setStatus($statusNouveau);
        }
        $this->logger->debug('Données reçues du formulaire', [
            'Creation' => $ticket->getCreationDate(),
            'email' => $ticket->getEmail(),
        ]);
        // The isSubmitted() call is mandatory because the isValid() method
        // throws an exception if the form has not been submitted.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            // Save the ticket to the database
            $this->logger->debug('Tentative d\'écriture BDD');
            try {
                $entityManager->persist($ticket);
                $entityManager->flush();

                // Flash messages are used to notify the user about the result of the
                // actions. They are deleted automatically from the session as soon
                // as they are accessed.
                // See https://symfony.com/doc/current/controller.html#flash-messages
                $this->addFlash('success', 'post.created_successfully');
                return $this->redirectToRoute('app_home');
            } catch (\Exception $e) {
                $this->logger->debug("Erreur lors de la création du ticket : " . $e->getMessage());
                $this->addFlash('error', 'post ticket creation failed');
            } 
        } else {
            $this->logger->debug('Formulaire non soumis ou invalide.');
        }
        return $this->render('app/createTicket.html.twig', [
            'CreateTicketForm' => $form->createView(),  // Pass the form view to the template
        ]);
    }
    /**
     * This controller is called directly via the render() function in the
     * blog/showBlog.html.twig template. That's why it's not needed to define
     * a route name for it.
     */
    // public function ticketForm(Ticket $ticket): Response
    // {
    //     $form = $this->createForm(CreateTicketType::class, $ticket);

    //     return $this->render('app/createTicket.html.twig', [
    //         'ticket' => $ticket,
    //         'form' => $form->createView(),
    //     ]);
    // }

    #[Route('/edit/{id}', name: 'app_ticket_edit', requirements: ['id' => '\d+'], methods: ['GET', 'POST'])]
    #[IsGranted('IS_AUTHENTICATED')]
    public function edit(
        Ticket $ticket, 
        Request $request, 
        EntityManagerInterface $entityManager): Response
    {
        // If the user is not authenticated, they will be redirected to the login page.
        // This is a security measure to ensure that only authenticated users can edit a ticket.
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }
        // Users and Editors have limited permission to update Category and Status.
        // Backup Category and Status to prevent loss of data.
        $originalCategory = $ticket->getCategory();
        $originalStatus = $ticket->getStatus();

        $form = $this->createForm(EditTicketType::class, $ticket);
        $form->handleRequest($request);

        // Status and Category are not editable by Users and Editors. Thus they are set to null in the form response.
        if($ticket->getStatus() === null) {
            // The status is null because user cannot set it (disabled in form), restore previous value
            $ticket->setStatus($originalStatus);
        }
        if($ticket->getCategory() === null) {
            // The category is null because user cannot set it (disabled in form), restore previous value
            $ticket->setCategory($originalCategory);
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Ticket mis à jour avec succès.');
            return $this->redirectToRoute('app_tickets_list'); // Redirige vers la liste des tickets
        }

        return $this->render('app/editTicket.html.twig', [
            'form' => $form->createView(),
            'ticket' => $ticket,
        ]);
    }

    #[Route('/delete/{id}', name: 'ticket_delete', requirements: ['id' => '\d+'], methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(
        Ticket $ticket, 
        EntityManagerInterface $entityManager, 
        Request $request): Response
    {
        // Vérifiez le token CSRF pour sécuriser la suppression
        if ($this->isCsrfTokenValid('delete_ticket_' . $ticket->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ticket);
            $entityManager->flush();
    
            $this->addFlash('success', 'Ticket supprimé avec succès.');
        } else {
            $this->addFlash('error', 'Échec de la suppression du ticket.');
        }

        return $this->redirectToRoute('app_tickets_list');
    }
}