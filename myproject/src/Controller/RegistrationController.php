<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\RegistrationFormType;
use App\Security\EmailVerifier;
use App\Security\EntrepriseAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, EntrepriseAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Entreprise();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            if (
                $form->get('plainPassword')->getData() ===
                $form->get('confirmPassword')->getData()
            ) {
                $user->setRoles(['ROLE_ENTREPRISE']);
                $user->setPassword(
                    $userPasswordHasher->hashPassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );
                $entityManager->persist($user);
                $entityManager->flush();
                return $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request

                );
            }
        } else {
            return $this->render('registration/register.html.twig', [
                'registrationForm' => $form->createView(),
                'passError' => "Les mots de passe ne sont pas indentiques",

            ]);
        }
        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
