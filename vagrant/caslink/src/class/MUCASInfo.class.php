<?php

//cas static wrapper class
class MUCASInfo
{
	public static function preloadCAS($casHost, $casPort, $casContextPath, $casCertPath)
  {
		require(dirname(__FILE__) . '/../cas-1.3.5/CAS.php');

		phpCAS::setDebug('debug.log');

		//create cas client
		phpCAS::client(CAS_VERSION_2_0, $casHost, $casPort, $casContextPath);

		//eis cert path - must be .pem
		$cert = dirname(__FILE__) . '/../..' . $casCertPath;

		//authenticate the ticket with eis cert
		//phpCAS::setCasServerCACert($cert);

		//skip ticket validation - not recommended in production
		phpCAS::setNoCasServerValidation();

		//run the auth
		phpCAS::forceAuthentication();
	}

	public function fetchUser()
  {
		return phpCAS::getUser();
	}

	public function fetchAttributes()
  {
		return phpCAS::getAttributes();
	}
}
