<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use App\Entity\Setting;
use App\Entity\Attribute;

class AppFixtures extends Fixture
{
  private $passwordEncoder;

  public function __construct(UserPasswordEncoderInterface $passwordEncoder)
  {
    $this->passwordEncoder = $passwordEncoder;
  }

  public function load(ObjectManager $manager)
  {
    //create default users
    $user = new User();
    $user->setUsername('demo');
    $user->setPassword($this->passwordEncoder->encodePassword($user, 'demo'));
    $user->setEmail('demo@demo.com');
    $user->setFirstName('Demo');
    $user->setLastName('Demo');
    $user->setRoles(['ROLE_ADMIN']);
    $manager->persist($user);

    //create default attributes
    $defaultAttributes = [
      ['friendlyName' => 'First Name', 'adAttribute' => 'givenName'],
      ['friendlyName' => 'Middle Name', 'adAttribute' => 'initials'],
      ['friendlyName' => 'Last Name', 'adAttribute' => 'sn'],
      ['friendlyName' => 'User Principal Name', 'adAttribute' => 'userPrincipalName'],
      ['friendlyName' => 'sAMAccountName', 'adAttribute' => 'sAMAccountName'],
      ['friendlyName' => 'Display Name', 'adAttribute' => 'displayName'],
      ['friendlyName' => 'Full Name', 'adAttribute' => 'name/cn'],
      ['friendlyName' => 'Description', 'adAttribute' => 'description'],
      ['friendlyName' => 'Office', 'adAttribute' => 'physicalDeliveryOfficeName'],
      ['friendlyName' => 'Telephone Number', 'adAttribute' => 'telephoneNumber'],
      ['friendlyName' => 'Email', 'adAttribute' => 'mail'],
      ['friendlyName' => 'Web Page', 'adAttribute' => 'wWWHomePage'],
      ['friendlyName' => 'Password', 'adAttribute' => 'password'],
      ['friendlyName' => 'Street', 'adAttribute' => 'streetAddress'],
      ['friendlyName' => 'PO Box', 'adAttribute' => 'postOfficeBox'],
      ['friendlyName' => 'City', 'adAttribute' => 'l'],
      ['friendlyName' => 'State/Province', 'adAttribute' => 'st'],
      ['friendlyName' => 'Zip/Postal Code', 'adAttribute' => 'postalCode'],
      ['friendlyName' => 'Country', 'adAttribute' => 'co'],
      ['friendlyName' => 'Country 2 Digit Code', 'adAttribute' => 'c'],
      ['friendlyName' => 'Country code', 'adAttribute' => 'countryCode'],
      ['friendlyName' => 'Groups', 'adAttribute' => 'memberOf'],
      ['friendlyName' => 'Remove from Groups', 'adAttribute' => 'removememberOf'],
      ['friendlyName' => 'Account Expires', 'adAttribute' => 'accountExpires'],
      ['friendlyName' => 'User Account Control ', 'adAttribute' => 'userAccountControl'],
      ['friendlyName' => 'Profile Path', 'adAttribute' => 'profilePath'],
      ['friendlyName' => 'Login Script', 'adAttribute' => 'scriptPath'],
      ['friendlyName' => 'Home Folder', 'adAttribute' => 'homeDirectory'],
      ['friendlyName' => 'Home Drive', 'adAttribute' => 'homeDrive'],
      ['friendlyName' => 'Log on to', 'adAttribute' => 'userWorkstations'],
      ['friendlyName' => 'Home', 'adAttribute' => 'homePhone'],
      ['friendlyName' => 'Pager', 'adAttribute' => 'pager'],
      ['friendlyName' => 'Mobile', 'adAttribute' => 'mobile'],
      ['friendlyName' => 'Fax', 'adAttribute' => 'facsimileTelephoneNumber'],
      ['friendlyName' => 'IP Phone', 'adAttribute' => 'ipPhone'],
      ['friendlyName' => 'Notes', 'adAttribute' => 'info'],
      ['friendlyName' => 'Title', 'adAttribute' => 'title'],
      ['friendlyName' => 'Department', 'adAttribute' => 'department'],
      ['friendlyName' => 'Company', 'adAttribute' => 'company'],
      ['friendlyName' => 'Manager', 'adAttribute' => 'manager'],
      ['friendlyName' => 'Mail Alias', 'adAttribute' => 'mailNickName'],
      ['friendlyName' => 'Simple Display Name', 'adAttribute' => 'displayNamePrintable'],
      ['friendlyName' => 'Hide from Exchange address lists', 'adAttribute' => 'msExchHideFromAddressLists'],
      ['friendlyName' => 'Sending Message Size', 'adAttribute' => 'submissionContLength'],
      ['friendlyName' => 'Receiving Message Size', 'adAttribute' => 'delivContLength'],
      ['friendlyName' => 'Accept messages from Authenticated Users only', 'adAttribute' => 'msExchRequireAuthToSendTo'],
      ['friendlyName' => 'Reject Messages From', 'adAttribute' => 'unauthOrig'],
      ['friendlyName' => 'Accept Messages From', 'adAttribute' => 'authOrig'],
      ['friendlyName' => 'Send on Behalf', 'adAttribute' => 'publicDelegates'],
      ['friendlyName' => 'Forward To', 'adAttribute' => 'altRecipient'],
      ['friendlyName' => 'Deliver and Redirect', 'adAttribute' => 'deliverAndRedirect'],
      ['friendlyName' => 'Reciepient Limits', 'adAttribute' => 'msExchRecipLimit'],
      ['friendlyName' => 'Use mailbox store defaults', 'adAttribute' => 'mDBuseDefaults'],
      ['friendlyName' => 'Issue Warning at', 'adAttribute' => 'mDBStorageQuota'],
      ['friendlyName' => 'Prohibit Send at', 'adAttribute' => 'mDBOverQuotaLimit'],
      ['friendlyName' => 'Prohibit Send and receive at', 'adAttribute' => 'mDBOverHardQuotaLimit'],
      ['friendlyName' => 'Garbage Collection Period', 'adAttribute' => 'garbageCollPeriod'],
      ['friendlyName' => 'Outlook Mobile Access ', 'adAttribute' => 'msExchOmaAdminWirelessEnable'],
      ['friendlyName' => 'Outlook Web Access ', 'adAttribute' => 'protocolSettings'],
      ['friendlyName' => 'Allow Terminal Server Logon', 'adAttribute' => 'tsAllowLogon'],
      ['friendlyName' => 'Terminal Services Profile Path', 'adAttribute' => 'tsProfilePath'],
      ['friendlyName' => 'Terminal Services Home Directory ', 'adAttribute' => 'tsHomeDir'],
      ['friendlyName' => 'Terminal Services Home Drive', 'adAttribute' => 'tsHomeDirDrive'],
      ['friendlyName' => 'Start the following program at logon', 'adAttribute' => 'tsInheritInitialProgram'],
      ['friendlyName' => 'Starting Program file name', 'adAttribute' => 'tsIntialProgram'],
      ['friendlyName' => 'Start in', 'adAttribute' => 'tsWorkingDir'],
      ['friendlyName' => 'Connect client drive at logon', 'adAttribute' => 'tsDeviceClientDrives'],
      ['friendlyName' => 'Connect client printer at logon', 'adAttribute' => 'tsDeviceClientPrinters'],
      ['friendlyName' => 'Default to main client printer', 'adAttribute' => 'tsDeviceClientDefaultPrinter'],
      ['friendlyName' => 'End disconnected session', 'adAttribute' => 'tsTimeOutSettingsDisConnections'],
      ['friendlyName' => 'Active Session limit', 'adAttribute' => 'tsTimeOutSettingsConnections'],
      ['friendlyName' => 'Idle session limit', 'adAttribute' => 'tsTimeOutSettingsIdle'],
      ['friendlyName' => 'When session limit reached or connection broken', 'adAttribute' => 'tsBrokenTimeOutSettings'],
      ['friendlyName' => 'Allow reconnection', 'adAttribute' => 'tsReConnectSettings'],
      ['friendlyName' => 'Remote Control', 'adAttribute' => 'tsShadowSettings'],
      ['friendlyName' => 'Protect accidental deletion', 'adAttribute' => 'preventDeletion'],
      ['friendlyName' => 'Manager can update members', 'adAttribute' => 'managerCanUpdateMembers'],
      ['friendlyName' => 'Primary Group ID', 'adAttribute' => 'primaryGroupID'],
      ['friendlyName' => 'Administrative Group', 'adAttribute' => 'msExchAdminGroup'],
      ['friendlyName' => 'Exchange Server Name', 'adAttribute' => 'msExchHomeServerName'],
      ['friendlyName' => 'Managed By', 'adAttribute' => 'managedBy'],
      ['friendlyName' => 'Target Address', 'adAttribute' => 'targetAddress'],
      ['friendlyName' => 'Add Proxy Addresses', 'adAttribute' => 'proxyAddresses'],
      ['friendlyName' => 'Remove Proxy Addresses', 'adAttribute' => 'removeproxyAddresses'],
      ['friendlyName' => 'Automatically Update Email-address based on Recipient Policy', 'adAttribute' => 'msExchPoliciesExcluded'],
      ['friendlyName' => 'Office 365 Group Membership', 'adAttribute' => 'GroupMemberObjectId'],
      ['friendlyName' => 'Enable Litigation Hold for Mailbox', 'adAttribute' => 'LitigationHoldEnabled'],
      ['friendlyName' => 'Litigation Hold Duration for Exchange Mailbox', 'adAttribute' => 'LitigationHoldDuration'],
      ['friendlyName' => 'Enable in-place Archive for User Mailbox', 'adAttribute' => 'InPlaceArchive'],
      ['friendlyName' => 'Archive Name for User\'s Mailbox Archive', 'adAttribute' => 'ArchiveName'],
      ['friendlyName' => 'User Principal Name of Office 365 user account', 'adAttribute' => 'O365userPrincipalName']
    ];

    foreach ($defaultAttributes as $attribute)
    {
      $record = new Attribute();
      $record->setFriendlyName($attribute['friendlyName']);
      $record->setAdAttribute($attribute['adAttribute']);
      $manager->persist($record);
    }

    //create default settings
    $settings = [];
    $settings['language'] = 'en';
    $settings['locale'] = 'en';
    $settings['siteName'] = 'DAS';
    $settings['siteHostname'] = 'http://localhost/';
    $settings['siteTimezone'] = 'America/New_York';
    $settings['sessionTimeout'] = 60; //in minutes
    $settings['casTicketTimeout'] = 1; //in minutes
    $settings['autoDeleteExpiredSessions'] = 0; //0 means never delete, else number of days

    foreach ($settings as $key => $value)
    {
      $setting = new Setting();
      $setting->setName($key);
      $setting->setValue($value);
      $manager->persist($setting);
    }

    $manager->flush();
  }
}
