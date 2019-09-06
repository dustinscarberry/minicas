<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Generator\SAML2Generator;
use App\Service\Generator\CASGenerator;
use App\Service\Generator\AuthGenerator;
use App\Model\SAML2Response;
use App\Entity\AuthenticatedSession;
use App\Entity\AuthenticatedService;
use App\Entity\ServiceProvider;
use App\Entity\CasTicket;
use Symfony\Component\Form\Exception\NotValidException;
use App\Service\Manager\AuthenticatedSessionManager;
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
    CASManager $casManager,
    ToolboxManager $toolboxManager
  )
  {
    //run cron session cleanup if needed
    $toolboxManager->cleanupExpiredSessions();

    //start processing saml response
    $samlResponse = $req->request->get('SAMLResponse');

    if (!$samlResponse)
      throw new NotValidException('No valid IDP SAML Response');

    //decode saml response
    $decodedSamlResponse = base64_decode($samlResponse);

    //create saml object
    $samlResponse = new SAML2Response();
    $samlResponse->loadFromString($decodedSamlResponse);

    //get saml attributes
    $authenticatedUser = $samlResponse->getSubject();
    $samlSessionId = $samlResponse->getSessionId();

    //load authenticated service from tracking id
    $authenticatedService = $this->getDoctrine()
      ->getRepository(AuthenticatedService::class)
      ->findByTrackingId($samlSessionId);

    if (!$authenticatedService)
      throw new NotValidException('Invalid user session');

    //get signing cert
    $signingCert = $authenticatedService->getService()->getIdentityProvider()->getCertificateData();

    //validate saml object
    $samlResponse->validate($signingCert);

    //get parameters from config
    $service = $authenticatedService->getService();
    $serviceType = $service->getType();
    $attributeMappings = $service->getAttributeMappings();
    $userFilterAttributeMapping = $service->getIdentityProvider()
      ->getUserAttributeMapping()
      ->getAdAttribute();

    //get user attribute override mapping for service if specified
    if ($service->getUserAttribute())
      $userAttributeMapping = $service->getUserAttribute()->getAdAttribute();
    else
      $userAttributeMapping = null;

    //get mapped attributes for authenticated user
    $mappedAttributes = AuthGenerator::resolveAttributes(
      $authenticatedUser,
      $userFilterAttributeMapping,
      $attributeMappings,
      $userAttributeMapping
    );

    //update user session
    $authenticatedService->getSession()->setUser($authenticatedUser);
    $authenticatedService->setAttributes($mappedAttributes);
    $this->getDoctrine()->getManager()->flush();

    //response based on service application type
    if ($serviceType == 'cas')
    {
      //create new cas ticket
      $casTicket = $casManager->createTicket($authenticatedService);

      //get redirect url
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
}
