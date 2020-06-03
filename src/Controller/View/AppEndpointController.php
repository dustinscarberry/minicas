<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Generator\CASGenerator;
use App\Service\Generator\AuthGenerator;
use App\Model\SAML2Response;
use App\Service\Manager\AuthenticatedSessionManager;
use App\Service\Manager\AuthenticatedServiceManager;
use App\Service\Manager\CASManager;
use App\Service\Manager\ToolboxManager;

class AppEndpointController extends AbstractController
{
  /**
   * @Route("/idpsamlvalidate", methods={"POST"})
   */
  public function idpSamlValidate(
    Request $req,
    AuthenticatedSessionManager $authSessionManager,
    AuthenticatedServiceManager $authServiceManager,
    CASManager $casManager,
    ToolboxManager $toolboxManager
  )
  {
    try
    {
      //run cron session cleanup if needed
      $toolboxManager->cleanupExpiredSessions();

      //start processing saml response
      $samlResponseData = $req->request->get('SAMLResponse');

      if (!$samlResponseData)
        throw new \Exception('No valid IDP SAML Response');

      //create saml object
      $samlResponse = new SAML2Response();
      $samlResponse->loadFromString($samlResponseData);

      //get saml attributes
      $authenticatedUser = $samlResponse->getSubject();
      $samlSessionId = $samlResponse->getSessionId();

      //load authenticated service from tracking id
      $authenticatedService = $authServiceManager->getServiceByTrackingId($samlSessionId);

      if (!$authenticatedService)
        throw new \Exception('Invalid user session');

      //get signing cert
      $signingCert = $authenticatedService
        ->getService()
        ->getIdentityProvider()
        ->getCertificateData();

      //validate saml object
      $samlResponse->validate($signingCert);

      //update session with username
      $authSessionManager->updateSessionUsername(
        $authenticatedService->getSession(),
        $authenticatedUser
      );

      //map service attributes for authenticated service
      $authenticatedService = $authServiceManager->mapServiceAttributes(
        $authenticatedService,
        $authenticatedUser
      );

      //respond based on service provider type
      $serviceType = $authenticatedService->getService()->getType();

      if ($serviceType == 'cas')
      {
        //create new cas ticket
        $casTicket = $casManager->createTicket($authenticatedService);

        //get cas redirect url
        $redirectURL = CASGenerator::getTicketRedirectUrl(
          $authenticatedService->getReplyTo(),
          $casTicket->getTicket()
        );

        //redirect to cas service and set cookie [commonauth]
        $response = new RedirectResponse($redirectURL);
        $cookie = AuthGenerator::createCommonAuthCookie(
          $authenticatedService->getSession()->getTrackingId()
        );
        $response->headers->setCookie($cookie);

        return $response;
      }
    }
    catch (\Exception $e)
    {
      throw $e;
    }
  }
}
