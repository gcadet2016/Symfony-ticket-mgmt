<?php
// Test de la fonctionnalité "User registration"
// Procédure de création décrite dans OneNote: Symfony -> User Authentication
namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
//use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\ErrorHandler\Exception\FlattenException;

class RegistrationController extends AbstractController
{

    public function __construct(
        // private EmailVerifier $emailVerifier,
        private LoggerInterface $logger // Injection du logger
    )
    {}

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, 
                            UserPasswordHasherInterface $userPasswordHasher, 
                            Security $security, 
                            EntityManagerInterface $entityManager
    ): Response {
        $user = new User();

        $username = $request->request->get('_username');    // Correspond au champ "name=_username"
        $email = $request->request->get('_email');          // Correspond au champ "name=_email"
        $plainPassword = $request->request->get('_pswd');        // Correspond au champ "name=_pswd"

        $this->logger->debug('Données reçues du formulaire', [
            'username' => $username,
            'email' => $email,
        ]);
        // Validation
        if (empty($username) || empty($email) || empty($plainPassword)) {
            $this->addFlash('error', 'Veuillez remplir tous les champs.');
            return $this->redirectToRoute('app_login');
        }
        // encode the plain password
        // hash the password (based on the security.yaml config for the $user class)
        
        $user->setUsername($username);
        if($user->getUsername() === 'admin') {
            // $this->logger->warning('Tentative de création d\'un utilisateur avec le nom "admin".');
            // $this->addFlash('error', 'Le nom d\'utilisateur "admin" est réservé.');
            // return $this->redirectToRoute('app_login');
            $user->setRoles(['ROLE_ADMIN']);
        } else {
            $user->setRoles(['ROLE_USER']);
        }
        //$user->setIsVerified(false);
        $user->setIsVerified(true); // Pour simplifier, on considère que l'utilisateur est vérifié à l'inscription

        $user->setEmail($email); // Commenté pour éviter le conflit avec l'annotation UniqueEntity
        $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

        try {
            $entityManager->persist($user);
            $entityManager->flush();
        } catch (\Exception $e) {
            $this->logger->error("Erreur lors de l\'enregistrement de l\'utilisateur : " . $e->getMessage());
            // $this->addFlash('error', $this->translator->trans('Une erreur est survenue lors de l\'inscription. Veuillez réessayer plus tard.', [], 'messages'));
            $this->addFlash('error', 'Une erreur est survenue lors de l\'inscription. Veuillez réessayer plus tard.');
            return $this->redirectToRoute('app_login');
        }

        $this->logger->info('Utilisateur enregistré avec succès.', [
            'username' => $username,
            'email' => $email,
        ]);
        
        //$this->logger->info('Calling $this->emailVerifier->sendEmailConfirmation.');
        // generate a signed url and email it to the user
        // $locale = $request->getLocale();
        // $this->logger->debug('Locale for email verification: ' . $locale);
        // if($locale === 'fr') {
        //     $email = 'registration/confirmation_email_fr.html.twig';
        // } elseif($locale === 'es') {
        //     $email = 'registration/confirmation_email_es.html.twig';
        // } elseif($locale === 'it') {
        //     $email = 'registration/confirmation_email_it.html.twig';
        // } else {
        //     $email = 'registration/confirmation_email_en.html.twig';
        // }
        // $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user, $locale,
        //     (new TemplatedEmail())
        //         // ->from(new Address('mailer@projet-idees.debrouillolab.fr', 'Acme Mail Bot'))
        //         ->from(new Address('idees.petitsdeb@gmail.com', 'IDEES email verify'))
        //         ->to((string) $user->getEmail())
        //         ->subject('Please Confirm your Email')
        //         ->htmlTemplate($email)
        // );  

        // return $security->login($user, 'form_login', 'main');

        $this->addFlash('success', 'Enregistrement réalisé avec succès.', [], 'messages');
        //$this->addFlash('verify_email', 'Email verification in progress');  // Utilisé pour autoriser le prochain login et permettre la validatino de l'email
        $this->logger->info('Redirection vers app_login.');
        return $this->redirectToRoute('app_login'); // Redirige vers une autre page après traitement
    }

}
