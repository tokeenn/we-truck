<?php

namespace App\Controller;

use App\Entity\Entreprise;
use App\Form\EntrepriseType;
use App\Security\AuthAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationEntrepriseController extends AbstractController
{
    #[Route('/registerE', name: 'app_registerE')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, AuthAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
    {
        $user = new Entreprise();
        $form = $this->createForm(EntrepriseType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (
                $form->get('plainPassword')->getData() === $form->get('confirmPassword')->getData()
            ) {
                $user->setRoles(['ROLE_ENTREPRISE']);
                
               
            
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

    } else {

        return $this->render('registration/registerE.html.twig', [
            'Entreprise' => $form->createView(),
            'passError' => "Les mots de passe ne sont pas indentiques",
        ]);
    }
        return $this->render('registration/registerE.html.twig', [
            'Entreprise' => $form->createView(),
        ]);
    }
}
