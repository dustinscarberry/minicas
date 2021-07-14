<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\CsrfTokenBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\PassportInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\EntryPoint\AuthenticationEntryPointInterface;

class DashboardAuthenticator extends AbstractAuthenticator implements AuthenticationEntryPointInterface
{
  use TargetPathTrait;

  private $router;

  public function __construct(RouterInterface $router)
  {
    $this->router = $router;
  }

  /**
   * Does the authenticator support the given Request?
   *
   * If this returns false, the authenticator will be skipped.
   *
   * Returning null means authenticate() can be called lazily when accessing the token storage.
   */
  public function supports(Request $request): ?bool
  {
    return ($request->attributes->get('_route') === 'dashboardLogin'
      && $request->isMethod('POST'));
  }

  /**
   * Create a passport for the current request.
   *
   * The passport contains the user, credentials and any additional information
   * that has to be checked by the Symfony Security system. For example, a login
   * form authenticator will probably return a passport containing the user, the
   * presented password and the CSRF token value.
   *
   * You may throw any AuthenticationException in this method in case of error (e.g.
   * a UserNotFoundException when the user cannot be found).
   *
   * @throws AuthenticationException
   */
  public function authenticate(Request $request): PassportInterface
  {
    $credentials = [
      'username' => $request->request->get('username'),
      'password' => $request->request->get('password'),
      'csrf_token' => $request->request->get('_csrf_token')
    ];

    return new Passport(
      new UserBadge($credentials['username']),
      new PasswordCredentials($credentials['password']),
      [new CsrfTokenBadge('authenticate', $credentials['csrf_token'])]
    );
  }

  /**
   * Called when authentication executed and was successful!
   *
   * This should return the Response sent back to the user, like a
   * RedirectResponse to the last page they visited.
   *
   * If you return null, the current request will continue, and the user
   * will be authenticated. This makes sense, for example, with an API.
   */
  public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
  {
    if ($targetPath = $this->getTargetPath($request->getSession(), $firewallName))
      return new RedirectResponse($targetPath);

    return new RedirectResponse($this->router->generate('dashboardHome'));
  }

  /**
   * Called when authentication executed, but failed (e.g. wrong username password).
   *
   * This should return the Response sent back to the user, like a
   * RedirectResponse to the login page or a 403 response.
   *
   * If you return null, the request will continue, but the user will
   * not be authenticated. This is probably not what you want to do.
   */
  public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
  {
    // return login page
    return new RedirectResponse($this->router->generate('dashboardLogin'));
  }

  /**
   * Returns a response that directs the user to authenticate.
   *
   * This is called when an anonymous request accesses a resource that
   * requires authentication. The job of this method is to return some
   * response that "helps" the user start into the authentication process.
   *
   * Examples:
   *
   * - For a form login, you might redirect to the login page
   *
   *     return new RedirectResponse('/login');
   *
   * - For an API token authentication system, you return a 401 response
   *
   *     return new Response('Auth header required', 401);
   *
   * @return Response
   */
  public function start(Request $request, AuthenticationException $authException = null)
  {
      return new RedirectResponse($this->router->generate('dashboardLogin'));
    }
}
