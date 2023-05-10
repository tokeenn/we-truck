<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\AbstractLoginFormAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Doctrine\ORM\EntityManagerInterface;

class AuthAuthenticator extends AbstractLoginFormAuthenticator
{
    private EntityManagerInterface $entityManager;
    public function __construct(private UrlGeneratorInterface $urlGenerator, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

   
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';



    public function authenticate(Request $request): Passport
    {
        
        $email = $request->request->get('email', '');
        
        $request->getSession()->set(Security::LAST_USERNAME, $email);
        $userLoader = function () use ($email) {
            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneByEmail($email);

            return $user;
        };
        $user = $userLoader();
        if (!$user) {
            throw new CustomUserMessageAuthenticationException('Invalid credentials.');
        }
    
        // Check if the user has the ROLE_USER role
        $userRoles = $user->getRoles();
        if (!in_array('[ROLE_ENTREPRISE]', $userRoles)) {
            throw new CustomUserMessageAuthenticationException('Access denied.');
        }


        return new Passport(
            
            new UserBadge($email,$userLoader),
            new PasswordCredentials($request->request->get('password', '')),
            [
                new CsrfTokenBadge('authenticate', $request->request->get('_csrf_token')),
            ]
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName)) {
            return new RedirectResponse($targetPath);
        }

        // For example:
        return new RedirectResponse($this->urlGenerator->generate('app_dashboard'));
        //throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
    }

    protected function getLoginUrl(Request $request): string
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }
}
