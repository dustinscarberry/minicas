<?php

namespace App\Controller\View;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Generator\CASGenerator;
use App\Service\Generator\SAML2Generator;
use App\Model\CAS1Response;
use App\Model\CAS2Response;
use App\Exception\InvalidTicketException;
use App\Exception\InvalidServiceException;
use App\Exception\InvalidRequestException;
use App\Service\Manager\CASManager;
use App\Service\Manager\AuthenticatedSessionManager;
use App\Service\Manager\AuthenticatedServiceManager;
use App\Model\AppConfig;

class CasEndpointController extends AbstractController
{
  /**
   * @Route("/cas/login")
   */
  public function casLogin(
    Request $req,
    SAML2Generator $saml2Generator,
    CASManager $casManager,
    AuthenticatedSessionManager $authSessionManager,
    AuthenticatedServiceManager $authServiceManager,
    AppConfig $appConfig
  )
  {
    try
    {
      //get params
      $service = $req->query->get('service');
      $commonAuthCookie = $req->cookies->get('commonauth');

      //get registered service
      $registeredService = $casManager->getServiceIfRegistered($service);

      //check for valid registered service
      if (!$registeredService)
        throw new InvalidServiceException('CAS service not registered or enabled');

      //get valid session
      $validSession = $authSessionManager->getSessionNotExpired($commonAuthCookie);

      if ($validSession)
      {
        $authenticatedService = $authServiceManager->getSessionService($validSession, $registeredService);

        if ($authenticatedService)
        {
          //create new cas ticket
          $casTicket = $casManager->createTicket($authenticatedService);

          //get redirect url
          $redirectURL = CASGenerator::getTicketRedirectUrl(
            $authenticatedService->getReplyTo(),
            $casTicket->getTicket()
          );

          //redirect to cas service and include ticket parameter
          return $this->redirect($redirectURL);
        }
        else
        {
          //create authenticated service
          $sessionService = $authServiceManager->createService($registeredService, $validSession, $service);

          //get parameters from config
          $attributeMappings = $registeredService->getAttributeMappings();
          $userFilterAttributeMapping = $registeredService->getIdentityProvider()
            ->getUserAttributeMapping()
            ->getAdAttribute();

          //get user attribute override mapping for service if specified
          if ($service->getUserAttribute())
            $userAttributeMapping = $registeredService->getUserAttribute()->getAdAttribute();
          else
            $userAttributeMapping = null;

          //get mapped attributes for authenticated user
          $mappedAttributes = AuthGenerator::resolveAttributes(
            $validSession->getUser(),
            $userFilterAttributeMapping,
            $attributeMappings,
            $userAttributeMapping
          );

          //update user session
          $sessionService->setAttributes($mappedAttributes);
          $this->getDoctrine()->getManager()->flush();

          //create new cas ticket
          $casTicket = $casManager->createTicket($sessionService);

          //get redirect url
          $redirectURL = CASGenerator::getTicketRedirectUrl(
            $sessionService->getReplyTo(),
            $casTicket->getTicket()
          );

          //redirect to cas service
          return $this->redirect($redirectURL);
        }
      }
      else
      {
        //create authenticated session
        $session = $authSessionManager->createSession();

        //create authenticated service
        $service = $authServiceManager->createService($registeredService, $session, $service);

        //redirect based on idp type
        if ($registeredService->getIdentityProvider()->getType() == 'saml2')
        {
          //get redirect url for idp
          $redirectURL = SAML2Generator::getRequestURL(
            $service->getTrackingId(),
            $registeredService->getIdentityProvider()->getLoginURL(),
            $registeredService->getIdentityProvider()->getIdentifier(),
            $appConfig->getSiteHostname() . '/idpsamlvalidate'
          );

          //redirect
          return $this->redirect($redirectURL);
        }
      }
    }
    catch (\Exception $e)
    {
      return CASGenerator::getErrorResponse($e);
    }
  }

  /**
   * @Route("/cas/serviceValidate")
   */
  public function serviceValidate(Request $req)
  {
    try
    {
      //get service and ticket params
      $service = $req->query->get('service');
      $ticket = $req->query->get('ticket');

      //check for required parameters
      if (empty($service) || empty($ticket))
        throw new InvalidRequestException("'service' and 'ticket' parameters are both required");

      //validate cas ticket and service
      $validCasTicket = $casManager->validateTicket($ticket, $service);

      //get authenticated user, service override or session default
      if ($validCasTicket->getService()->getAttributes()['user'])
        $authUser = $validCasTicket->getService()->getAttributes()['user'];
      else
        $authUser = $validCasTicket->getService()->getSession()->getUser();

      //create cas response
      $casResponse = new CAS1Response(
        $authUser,
        true
      );

      //send cas response
      $response = new Response(
        $casResponse->getXML(),
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );

      return $response;
    }
    catch (\Exception $e)
    {
      return CASGenerator::getErrorResponse($e);
    }
  }

  /**
   * @Route("/cas/p3/serviceValidate")
   */
  public function serviceValidateP3(Request $req, CASManager $casManager)
  {
    try
    {
      //get service and ticket params
      $service = $req->query->get('service');
      $ticket = $req->query->get('ticket');

      //check for required parameters
      if (empty($service) || empty($ticket))
        throw new InvalidRequestException("'service' and 'ticket' parameters are both required");

      //validate cas ticket and service
      $validCasTicket = $casManager->validateTicket($ticket, $service);

      //get authenticated user, service override or session default
      if ($validCasTicket->getService()->getAttributes()->user)
        $authUser = $validCasTicket->getService()->getAttributes()->user;
      else
        $authUser = $validCasTicket->getService()->getSession()->getUser();

      //create cas response
      $casResponse = new CAS2Response(
        $authUser,
        $validCasTicket->getService()->getAttributes()->attributes,
        true
      );

      //send cas response
      $response = new Response(
        $casResponse->getXML(),
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );

      return $response;
    }
    catch (\Exception $e)
    {
      throw $e;
      return CASGenerator::getErrorResponse($e);
    }
  }









  /**
   * @Route("/cas/samlValidate", methods={"POST"})
   */
  public function samlValidate(Request $req, CASManager $casManager)
  {
    try
    {

      dd('test');
      
      //get service and ticket params
      $service = $req->query->get('TARGET');
      $ticket = $req->query->get('ticket');

      //check for required parameters
      if (empty($service) || empty($ticket))
        throw new InvalidRequestException("'TARGET' and 'ticket' parameters are both required");

      //validate cas ticket and service
      $validCasTicket = $casManager->validateTicket($ticket, $service);

      //get authenticated user, service override or session default
      if ($validCasTicket->getService()->getAttributes()->user)
        $authUser = $validCasTicket->getService()->getAttributes()->user;
      else
        $authUser = $validCasTicket->getService()->getSession()->getUser();

      //create cas response
      $casResponse = new CAS2Response(
        $authUser,
        $validCasTicket->getService()->getAttributes()->attributes,
        true
      );

      //send cas response
      $response = new Response(
        $casResponse->getXML(),
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );

      return $response;
    }
    catch (\Exception $e)
    {
      throw $e;
      return CASGenerator::getErrorResponse($e);
    }
  }











  /**
   * @Route("/cas/logout")
   */
  public function logout(Request $req, AuthenticatedSessionManager $authSessionManager)
  {
    //get service and ticket params
    $service = $req->query->get('service');
    $commonAuthCookie = $req->cookies->get('commonauth');

    //get valid session
    $validSession = $authSessionManager->getSessionNotExpired($commonAuthCookie);

    //delete session
    if ($validSession)
      $authSessionManager->deleteSession($validSession);

    //redirect to service if provided
    if ($service)
      $response = new RedirectResponse($service);
    else
      $response = $this->render('app/caslogout/view.html.twig');

    //clear cookie
    $response->headers->clearCookie('commonauth');
    return $response;
  }
}
