<?php

namespace App\Controller\View\App;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Generator\CASGenerator;
use App\Service\Generator\AuthGenerator;
use App\Model\SAML2Response;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Service\Factory\AuthenticatedServiceFactory;
use App\Service\Manager\CASManager;

class AppEndpointController extends AbstractController
{
  /**
   * @Route("/idpsamlvalidate", methods={"POST"})
   */
  public function idpSamlValidate(
    Request $req,
    AuthenticatedSessionFactory $authSessionFactory,
    AuthenticatedServiceFactory $authServiceFactory,
    CASManager $casManager
  )
  {
    try
    {
      //run cron session cleanup if needed
      $authSessionFactory->cleanupExpiredSessions();

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
      $authenticatedService = $authServiceFactory->getServiceByTrackingId($samlSessionId);

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
      $authSessionFactory->updateSessionUsername(
        $authenticatedService->getSession(),
        $authenticatedUser
      );

      //map service attributes for authenticated service
      $authenticatedService = $authServiceFactory->mapServiceAttributes(
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
