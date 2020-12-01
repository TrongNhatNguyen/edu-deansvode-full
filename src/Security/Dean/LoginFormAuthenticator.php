<?php

namespace App\Security\Dean;

use App\DTO\Request\CaptchaRequest;
use App\Entity\Dean;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'web_home';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $captchaRequest;
    private $validator;
    private $router;

    public function __construct(
        EntityManagerInterface $entityManager,
        UrlGeneratorInterface $urlGenerator,
        CsrfTokenManagerInterface $csrfTokenManager,
        CaptchaRequest $captchaRequest,
        ValidatorInterface $validator,
        RouterInterface $router
    ) {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->captchaRequest = $captchaRequest;
        $this->validator = $validator;
        $this->router = $router;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email1' => $request->request->get('email'),
            'password1' => $request->request->get('password'),
            'captcha_string' => $request->request->get('captcha_string'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];

        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email1']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if ($credentials['email1'] && $credentials['password1']) {
            // Captcha:
            if (!$credentials['captcha_string']) {
                throw new InvalidCsrfTokenException('Enter secutity-code.');
            }
        }
        
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException('s1');
        }

        // email:
        if (!$this->isValidEmail($credentials['email1'])) {
            throw new InvalidCsrfTokenException('Enter your email');
        }

        $user = $this->entityManager->getRepository(Dean::class)->findOneBy(['email1' => $credentials['email1']]);

        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Email could not be found.');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$credentials['password1']) {
            throw new CustomUserMessageAuthenticationException('Enter your password');
        }

        // Captcha:
        if ($this->getValidCaptcha($credentials['captcha_string']) > 0) {
            throw new CustomUserMessageAuthenticationException('secutity-code is valide, try again.');
        }

        if ($user->getPassword() !== $credentials['password1']) {
            throw new CustomUserMessageAuthenticationException('password is correct.');
        }

        return true;
    }

    public function getPassword($credentials): ?string
    {
        return $credentials['password1'];
    }

    // authentication Success:
    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        return new JsonResponse([
            'status' => 'success',
            'message' => 'login is successfully!',
            'url_response' => $this->router->generate('dean_voting_session')
        ]);

        // return new RedirectResponse($this->urlGenerator->generate('dean_voting_session'));
    }

    // authentication failed:
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        return new JsonResponse([
            'status' => 'failed',
            'message' => 'login is failed!',
            'error' => $exception->getMessage(),
        ]);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }


    //------------- check validate request data login:
    public function getValidCaptcha($captchaString)
    {
        $this->captchaRequest->buildByData($captchaString);
        $errors = $this->validator->validate($this->captchaRequest);

        return count($errors);
    }

    private function isValidEmail($email)
    {
        return strpos($email, '@') !== false;
    }
}
