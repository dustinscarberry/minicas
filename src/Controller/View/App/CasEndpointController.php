<?php

namespace App\Controller\View\App;


use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Service\Generator\CASGenerator;
use App\Service\Generator\SAML2Generator;
use App\Service\Generator\AuthGenerator;
use App\Model\CAS1Response;
use App\Model\CAS2Response;
use App\Exception\InvalidTicketException;
use App\Exception\InvalidServiceException;
use App\Exception\InvalidRequestException;
use App\Service\Factory\CasTicketFactory;
use App\Service\Factory\AuthenticatedSessionFactory;
use App\Service\Factory\AuthenticatedServiceFactory;
use App\Service\Factory\ServiceProviderFactory;
use App\Service\Factory\InvalidServiceFactory;
use App\Model\AppConfig;
use Exception;

class CasEndpointController extends AbstractController
{
  #[Route('/cas/login')]
  public function casLogin(
    Request $req,
    SAML2Generator $saml2Generator,
    CasTicketFactory $casTicketFactory,
    AuthenticatedSessionFactory $authSessionFactory,
    AuthenticatedServiceFactory $authServiceFactory,
    ServiceProviderFactory $serviceProviderFactory,
    InvalidServiceFactory $invalidServiceFactory,
    AppConfig $appConfig
  ) {
    try {
      // get params
      $service = $req->query->get('service');
      $commonAuthCookie = $req->cookies->get('commonauth');

      if (!$service) throw new Exception('Invalid params');

      // get remote ip address
      if ($req->server->get('HTTP_X_FORWARDED_FOR'))
        $remoteIp = $req->server->get('HTTP_X_FORWARDED_FOR');
      else
        $remoteIp = $req->server->get('REMOTE_ADDR');

      // get registered service
      $registeredService = $serviceProviderFactory->getServiceIfRegistered($service);

      // check for valid registered service
      if (!$registeredService) {
        $invalidServiceFactory->createInvalidService($service, $remoteIp);
        throw new InvalidServiceException('CAS service not registered or enabled');
      }

      //get valid session
      $validSession = $authSessionFactory->getSessionNotExpired($commonAuthCookie);

      if ($validSession) {
        //get matching authenticated service from authenticated session
        $authenticatedService = $authServiceFactory->getSessionService(
          $validSession,
          $service
        );

        if ($authenticatedService) {
          //create new cas ticket
          $casTicket = $casTicketFactory->createTicket($authenticatedService);

          //get cas redirect url
          $redirectURL = CASGenerator::getTicketRedirectUrl(
            $authenticatedService->getReplyTo(),
            $casTicket->getTicket()
          );

          //redirect to cas service
          return $this->redirect($redirectURL);
        } else {
          //create authenticated service
          $sessionService = $authServiceFactory->createService(
            $registeredService,
            $validSession,
            $service
          );

          //map service attributes for authenticated service
          $sessionService = $authServiceFactory->mapServiceAttributes(
            $sessionService,
            $validSession->getUser()
          );

          //create new cas ticket
          $casTicket = $casTicketFactory->createTicket($sessionService);

          //get cas redirect url
          $redirectURL = CASGenerator::getTicketRedirectUrl(
            $sessionService->getReplyTo(),
            $casTicket->getTicket()
          );

          //redirect to cas service
          return $this->redirect($redirectURL);
        }
      } else {
        //create authenticated session
        $session = $authSessionFactory->createSession($remoteIp);

        //create authenticated service
        $service = $authServiceFactory->createService($registeredService, $session, $service);

        //redirect based on idp type
        if ($registeredService->getIdentityProvider()->getType() == 'saml2')
        {
          //get redirect url for idp
          $redirectURL = SAML2Generator::getRequestURL(
            $service->getTrackingId(),
            $registeredService->getIdentityProvider()->getLoginURL(),
            $registeredService->getIdentityProvider()->getIdentifier(),
            $_ENV['APP_HOST'] . '/idpsamlvalidate'
          );

          //redirect
          return $this->redirect($redirectURL);
        }
      }
    } catch (\Exception $e) {
      // send cas response
      return new Response(
        CASGenerator::getErrorResponse($e),
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );
    }
  }

  #[Route('/cas/serviceValidate')]
  public function serviceValidate(Request $req, CasTicketFactory $casTicketFactory)
  {
    try {
      //get service and ticket params
      $service = $req->query->get('service');
      $ticket = $req->query->get('ticket');

      //check for required parameters
      if (empty($service) || empty($ticket))
        throw new InvalidRequestException("'service' and 'ticket' parameters are both required");

      //validate cas ticket and service
      $validCasTicket = $casTicketFactory->validateTicket($ticket, $service);

      //get authenticated user, service override or session default
      if ($validCasTicket->getService()->getAttributes()->user)
        $authUser = $validCasTicket->getService()->getAttributes()->user;
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
    } catch (\Exception $e) {
      // send cas response
      return new Response(
        CASGenerator::getErrorResponse($e),
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );
    }
  }

  #[Route('/cas/p3/serviceValidate')]
  public function serviceValidateP3(Request $req, CasTicketFactory $casTicketFactory)
  {
    try {
      //get service and ticket params
      $service = $req->query->get('service');
      $ticket = $req->query->get('ticket');

      //check for required parameters
      if (empty($service) || empty($ticket))
        throw new InvalidRequestException("'service' and 'ticket' parameters are both required");

      //validate cas ticket and service
      $validCasTicket = $casTicketFactory->validateTicket($ticket, $service);

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
    } catch (\Exception $e) {
      // send cas response
      return new Response(
        CASGenerator::getErrorResponse($e),
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );
    }
  }

  #[Route('/cas/samlValidate', methods: ['POST'])]
  public function samlValidate(Request $req, CasTicketFactory $casTicketFactory)
  {
    try {
      //get saml request string
      $body = $req->getContent();

      //parse xml request string
      $xml = simplexml_load_string($body);
      $node = $xml->xpath('//SOAP-ENV:Body')[0];
      $node->registerXPathNamespace('samlp', 'urn:oasis:names:tc:SAML:1.0:protocol');
      $requestId = (string)$node->xpath('samlp:Request')[0]->attributes()['RequestID'];
      $ticket = (string)$node->xpath('samlp:Request/samlp:AssertionArtifact')[0];

      //get service param
      $service = $req->query->get('TARGET');

      //check for required parameters
      if (empty($service) || empty($ticket))
        throw new InvalidRequestException("'TARGET' and SAMLRequest parameters are both required");

      //validate cas ticket and service
      $validCasTicket = $casTicketFactory->validateTicket($ticket, $service);

      //get authenticated user, service override or session default
      if ($validCasTicket->getService()->getAttributes()->user)
        $authUser = $validCasTicket->getService()->getAttributes()->user;
      else
        $authUser = $validCasTicket->getService()->getSession()->getUser();

      //get timestamps
      $start = date('Y-m-d\TH:i:s:u\Z');
      $end = date('Y-M-D\TH:i:s:u\Z', time() + 30);

      //get attribute string
      $attributes = '';

      foreach ($validCasTicket->getService()->getAttributes()->attributes as $attr)
        $attributes .= '<Attribute AttributeName="' . $attr->name . '" AttributeNamespace="http://www.ja-sig.org/products/cas/"><AttributeValue>' . $attr->value . '</AttributeValue></Attribute>';

      //build saml1.1 response
      $samlRsp = '<?xml version="1.0" encoding="UTF-8"?>';
      $samlRsp .= '<SOAP-ENV:Envelope xmlns:SOAP-ENV="http://schemas.xmlsoap.org/soap/envelope/">';
      $samlRsp .= '<SOAP-ENV:Header />';
      $samlRsp .= '<SOAP-ENV:Body>';
      $samlRsp .= '<Response xmlns="urn:oasis:names:tc:SAML:1.0:protocol" xmlns:saml="urn:oasis:names:tc:SAML:1.0:assertion" xmlns:samlp="urn:oasis:names:tc:SAML:1.0:protocol" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" IssueInstant="' . $start . '" MajorVersion="1" MinorVersion="1" Recipient="' . $service . '" ResponseID="' . $requestId . '">';
      $samlRsp .= '<Status><StatusCode Value="samlp:Success"></StatusCode></Status>';
      $samlRsp .= '<Assertion xmlns="urn:oasis:names:tc:SAML:1.0:assertion" AssertionID="_e5c23ff7a3889e12fa01802a47331653" IssueInstant="' . $start . '" Issuer="https://auth.marshall.edu" MajorVersion="1" MinorVersion="1">';
      $samlRsp .= '<Conditions NotBefore="' . $start . '" NotOnOrAfter="' . $end . '"><AudienceRestrictionCondition><Audience>' . $service . '</Audience></AudienceRestrictionCondition></Conditions>';
      $samlRsp .= '<AttributeStatement><Subject><NameIdentifier>' . $authUser . '</NameIdentifier><SubjectConfirmation><ConfirmationMethod>urn:oasis:names:tc:SAML:1.0:cm:artifact</ConfirmationMethod></SubjectConfirmation></Subject>' . $attributes . '</AttributeStatement>';
      $samlRsp .= '<AuthenticationStatement AuthenticationInstant="' . $start . '" AuthenticationMethod="urn:oasis:names:tc:SAML:1.0:am:password"><Subject><NameIdentifier>' . $authUser . '</NameIdentifier><SubjectConfirmation><ConfirmationMethod>urn:oasis:names:tc:SAML:1.0:cm:artifact</ConfirmationMethod></SubjectConfirmation></Subject></AuthenticationStatement>';
      $samlRsp .= '</Assertion></Response></SOAP-ENV:Body></SOAP-ENV:Envelope>';

      //send cas saml1.1 response
      $response = new Response(
        $samlRsp,
        Response::HTTP_OK,
        ['Content-Type', 'text/xml']
      );

      return $response;
    } catch (\Exception $e) {
      throw $e;
    }
  }

  #[Route('/cas/logout')]
  public function logout(Request $req, AuthenticatedSessionFactory $authSessionFactory)
  {
    //get service and ticket params
    $service = $req->query->get('service');
    $commonAuthCookie = $req->cookies->get('commonauth');

    //get valid session
    $validSession = $authSessionFactory->getSessionNotExpired($commonAuthCookie);

    //delete session
    if ($validSession)
      $authSessionFactory->deleteSession($validSession);

    //redirect to service if provided
    if ($service)
      $response = new RedirectResponse($service);
    else
      $response = $this->render('app/caslogout/view.html.twig');

    //clear cookie
    $response->headers->clearCookie('commonauth');
    return $response;
  }

  #[Route('/cas/certificate')]
  public function downloadCASCertificate()
  {
    $context = stream_context_create (['ssl' => ['capture_peer_cert' => true]]);
    $stream = fopen($_ENV['APP_HOST'], 'rb', false, $context);
    $streamParams = stream_context_get_params($stream);
    openssl_x509_export($streamParams['options']['ssl']['peer_certificate'], $certificateData);

    $response = new Response($certificateData);
    $disposition = HeaderUtils::makeDisposition(
      HeaderUtils::DISPOSITION_ATTACHMENT,
      'certificate.cer'
    );
    $response->headers->set('Content-Disposition', $disposition);

    return $response;
  }
}
