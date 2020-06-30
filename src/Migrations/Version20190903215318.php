<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190903215318 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cas_ticket (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, ticket VARCHAR(255) NOT NULL, created INT NOT NULL, validated TINYINT(1) NOT NULL, expiration INT NOT NULL, INDEX IDX_436241CFED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authenticated_service (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, session_id INT NOT NULL, attributes LONGTEXT DEFAULT NULL, tracking_id VARCHAR(255) NOT NULL, reply_to VARCHAR(255) NOT NULL, INDEX IDX_80236C47ED5CA9E6 (service_id), INDEX IDX_80236C47613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE authenticated_session (id INT AUTO_INCREMENT NOT NULL, tracking_id VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, user VARCHAR(255) DEFAULT NULL, expiration INT NOT NULL, INDEX tracking_id_idx (tracking_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, hash_id VARCHAR(25) NOT NULL, username VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX user_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute (id INT AUTO_INCREMENT NOT NULL, hash_id VARCHAR(25) NOT NULL, friendly_name VARCHAR(255) NOT NULL, ad_attribute VARCHAR(255) NOT NULL, deleted TINYINT(1) NOT NULL, INDEX attribute_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE attribute_mapping (id INT AUTO_INCREMENT NOT NULL, service_id INT NOT NULL, ad_attribute_id INT NOT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_C27A754EED5CA9E6 (service_id), INDEX IDX_C27A754E5AAE970F (ad_attribute_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE identity_provider (id INT AUTO_INCREMENT NOT NULL, user_attribute_mapping_id INT NOT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, login_url VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, certificate LONGTEXT DEFAULT NULL, INDEX IDX_D12F2F5540FCA3D6 (user_attribute_mapping_id), INDEX idp_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service_provider (id INT AUTO_INCREMENT NOT NULL, identity_provider_id INT NOT NULL, user_attribute_id INT DEFAULT NULL, hash_id VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, identifier VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, INDEX IDX_6BB228A1B5FB2C8E (identity_provider_id), INDEX IDX_6BB228A1362A52F8 (user_attribute_id), INDEX sp_hashid_idx (hash_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE setting (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cas_ticket ADD CONSTRAINT FK_436241CFED5CA9E6 FOREIGN KEY (service_id) REFERENCES authenticated_service (id)');
        $this->addSql('ALTER TABLE authenticated_service ADD CONSTRAINT FK_80236C47ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service_provider (id)');
        $this->addSql('ALTER TABLE authenticated_service ADD CONSTRAINT FK_80236C47613FECDF FOREIGN KEY (session_id) REFERENCES authenticated_session (id)');
        $this->addSql('ALTER TABLE attribute_mapping ADD CONSTRAINT FK_C27A754EED5CA9E6 FOREIGN KEY (service_id) REFERENCES service_provider (id)');
        $this->addSql('ALTER TABLE attribute_mapping ADD CONSTRAINT FK_C27A754E5AAE970F FOREIGN KEY (ad_attribute_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE identity_provider ADD CONSTRAINT FK_D12F2F5540FCA3D6 FOREIGN KEY (user_attribute_mapping_id) REFERENCES attribute (id)');
        $this->addSql('ALTER TABLE service_provider ADD CONSTRAINT FK_6BB228A1B5FB2C8E FOREIGN KEY (identity_provider_id) REFERENCES identity_provider (id)');
        $this->addSql('ALTER TABLE service_provider ADD CONSTRAINT FK_6BB228A1362A52F8 FOREIGN KEY (user_attribute_id) REFERENCES attribute (id)');

        // add default seed data
        $this->addSql("INSERT INTO attribute
          (id, hash_id, friendly_name, ad_attribute, deleted) VALUES
          (1,'nm7Od6M1jqPBY','First Name','givenName',0),
          (2,'V6ldga2N730ZL','Middle Name','initials',0),
          (3,'47Dg5J3kNxjjO','Last Name','sn',0),
          (4,'RL9oWrjxM2q3','User Principal Name','userPrincipalName',0),
          (5,'VVEZmx44GBqmG','sAMAccountName','sAMAccountName',0),
          (6,'W4O5XY1b4njYK','Display Name','displayName',0),
          (7,'bYLOZq3nQ55X6','Description','description',0),
          (8,'Mql86d9B9J1zg','Office','physicalDeliveryOfficeName',0),
          (9,'7049GrJz2Z37l','Telephone Number','telephoneNumber',0),
          (10,'Erd8wqJGO2NXo','Email','mail',0),
          (11,'qZNPWQrOLmxaM','Web Page','wWWHomePage',0),
          (12,'VVEZmx488QwEg','Password','password',0),
          (13,'rXWPZQk04xlOj','Street','streetAddress',0),
          (14,'aKldLkNRYqED2','PO Box','postOfficeBox',0),
          (15,'Jow82YbmEMw2P','City','l',0),
          (16,'WP3aoVbdml9BB','State/Province','st',0),
          (17,'Lrl86kXGVpQLQ','Zip/Postal Code','postalCode',0),
          (18,'wr7OXLg00mw23','Country','co',0),
          (19,'Q1xjG0LZ1W2Pq','Country 2 Digit Code','c',0),
          (20,'8pgZR4zM0gWD4','Country code','countryCode',0),
          (21,'rXWPZQkEQ2QnO','Groups','memberOf',0),
          (22,'gz2PbQMqx8gb8','Remove from Groups','removememberOf',0),
          (23,'WP3aoVbQxPO1a','Account Expires','accountExpires',0),
          (24,'Mql86d9Brzgzd','User Account Control ','userAccountControl',0),
          (25,'DgW83YL7BjbaX','Profile Path','profilePath',0),
          (26,'O5l86Ygk7dXjn','Login Script','scriptPath',0),
          (27,'0bwMz4O1jO9D7','Home Folder','homeDirectory',0),
          (28,'noNwzW9OxndqG','Home Drive','homeDrive',0),
          (29,'XxO34GDYMGGbw','Log on to','userWorkstations',0),
          (30,'mkQ6YzX3mmYD2','Home','homePhone',0),
          (31,'X6QBXKq4mm787','Pager','pager',0),
          (32,'zxDWoQz9NgqqJ','Mobile','mobile',0),
          (33,'3zbjYLk3arzx3','Fax','facsimileTelephoneNumber',0),
          (34,'mMgO8xJ39VJDw','IP Phone','ipPhone',0),
          (35,'ZGl1Dq9EaRL3L','Notes','info',0),
          (36,'X6QBXKqW6YQGO','Title','title',0),
          (37,'0NmDpqKzBDk87','Department','department',0),
          (38,'7ljRkoq9ddLom','Company','company',0),
          (39,'kV2PNBb8gBRkX','Manager','manager',0),
          (40,'Lrl86kXE55867','Mail Alias','mailNickName',0),
          (41,'7049GrJJOLw60','Simple Display Name','displayNamePrintable',0),
          (42,'za5BgmRdllrYx','Hide from Exchange address lists','msExchHideFromAddressLists',0),
          (43,'bYLOZq3Zm1Yxg','Sending Message Size','submissionContLength',0),
          (44,'4jMbW90PO5G13','Receiving Message Size','delivContLength',0),
          (45,'mkQ6YzXJb1ld7','Accept messages from Authenticated Users only','msExchRequireAuthToSendTo',0),
          (46,'KP5oGKkDlwWQq','Reject Messages From','unauthOrig',0),
          (47,'47Dg5J3K3o0Kj','Accept Messages From','authOrig',0),
          (48,'zxDWoQzBjQ6Zr','Send on Behalf','publicDelegates',0),
          (49,'47Dg5J3WWZNZ3','Forward To','altRecipient',0),
          (50,'xa7O9Jrdr1gWG','Deliver and Redirect','deliverAndRedirect',0),
          (51,'oo7OLwmaJ86kp','Reciepient Limits','msExchRecipLimit',0),
          (52,'bKkdJ6nZ3X4EV','Use mailbox store defaults','mDBuseDefaults',0),
          (53,'llVmRMNZ1E7d','Issue Warning at','mDBStorageQuota',0),
          (54,'47Dg5J3PpWYWx','Prohibit Send at','mDBOverQuotaLimit',0),
          (55,'lg2P6YDOb7xpQ','Prohibit Send and receive at','mDBOverHardQuotaLimit',0),
          (56,'6roXdgLWP3xro','Garbage Collection Period','garbageCollPeriod',0),
          (57,'4jMbW90PLYdz6','Outlook Mobile Access ','msExchOmaAdminWirelessEnable',0),
          (58,'1DwmEb0qqdoNB','Outlook Web Access ','protocolSettings',0),
          (59,'WP3aoVbd4qBo1','Allow Terminal Server Logon','tsAllowLogon',0),
          (60,'jVPJrLajJklp','Terminal Services Profile Path','tsProfilePath',0),
          (61,'BQZ8dW5r3o4w3','Terminal Services Home Directory ','tsHomeDir',0),
          (62,'bYLOZq3DldJ66','Terminal Services Home Drive','tsHomeDirDrive',0),
          (63,'DgW83YLGmowEn','Start the following program at logon','tsInheritInitialProgram',0),
          (64,'NXl860jNjr1GZ','Starting Program file name','tsIntialProgram',0),
          (65,'W4O5XY1QgYGWQ','Start in','tsWorkingDir',0),
          (66,'Lrl86kX4jkQRo','Connect client drive at logon','tsDeviceClientDrives',0),
          (67,'qb6OMx42DjOPq','Connect client printer at logon','tsDeviceClientPrinters',0),
          (68,'MVo5kQz3bgEl','Default to main client printer','tsDeviceClientDefaultPrinter',0),
          (69,'bYLOZq3Z10g5m','End disconnected session','tsTimeOutSettingsDisConnections',0),
          (70,'Gz08RYmLMWaLm','Active Session limit','tsTimeOutSettingsConnections',0),
          (71,'p47OJxo1Mpkpm','Idle session limit','tsTimeOutSettingsIdle',0),
          (72,'Erd8wqJGVjORo','When session limit reached or connection broken','tsBrokenTimeOutSettings',0),
          (73,'Gw1LG93GbRG6Q','Allow reconnection','tsReConnectSettings',0),
          (74,'mMgO8xJREMYgm','Remote Control','tsShadowSettings',0),
          (75,'47Dg5J3bXq3qQ','Protect accidental deletion','preventDeletion',0),
          (76,'bYLOZq34BOXRg','Manager can update members','managerCanUpdateMembers',0),
          (77,'za5BgmRdJ5wbz','Primary Group ID','primaryGroupID',0),
          (78,'ZGl1Dq9dQnNpX','Administrative Group','msExchAdminGroup',0),
          (79,'93raxbEGLopBM','Exchange Server Name','msExchHomeServerName',0),
          (80,'X6QBXKq4BNGW8','Managed By','managedBy',0),
          (81,'47Dg5J3bNWDrb','Target Address','targetAddress',0),
          (82,'Q1xjG0LKbJozQ','Add Proxy Addresses','proxyAddresses',0),
          (83,'MPObGZY3WwW9g','Remove Proxy Addresses','removeproxyAddresses',0),
          (84,'bKkdJ6n35pJXg','Automatically Update Email-address based on Recipient Policy','msExchPoliciesExcluded',0),
          (85,'QOld6kK2xnNZ9','Office 365 Group Membership','GroupMemberObjectId',0),
          (86,'2p49dqZoVx68m','Enable Litigation Hold for Mailbox','LitigationHoldEnabled',0),
          (87,'rDw6mxbYdXGYp','Litigation Hold Duration for Exchange Mailbox','LitigationHoldDuration',0),
          (88,'YDndj6zX2DlkV','Enable in-place Archive for User Mailbox','InPlaceArchive',0),
          (89,'yzBLzlk32jnX','Archive Name for User\'s Mailbox Archive','ArchiveName',0),
          (90,'VVEZmx4mY6zMG','User Principal Name of Office 365 user account','O365userPrincipalName',0)
        ");
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cas_ticket DROP FOREIGN KEY FK_436241CFED5CA9E6');
        $this->addSql('ALTER TABLE authenticated_service DROP FOREIGN KEY FK_80236C47613FECDF');
        $this->addSql('ALTER TABLE attribute_mapping DROP FOREIGN KEY FK_C27A754E5AAE970F');
        $this->addSql('ALTER TABLE identity_provider DROP FOREIGN KEY FK_D12F2F5540FCA3D6');
        $this->addSql('ALTER TABLE service_provider DROP FOREIGN KEY FK_6BB228A1362A52F8');
        $this->addSql('ALTER TABLE service_provider DROP FOREIGN KEY FK_6BB228A1B5FB2C8E');
        $this->addSql('ALTER TABLE authenticated_service DROP FOREIGN KEY FK_80236C47ED5CA9E6');
        $this->addSql('ALTER TABLE attribute_mapping DROP FOREIGN KEY FK_C27A754EED5CA9E6');
        $this->addSql('DROP TABLE cas_ticket');
        $this->addSql('DROP TABLE authenticated_service');
        $this->addSql('DROP TABLE authenticated_session');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE attribute');
        $this->addSql('DROP TABLE attribute_mapping');
        $this->addSql('DROP TABLE identity_provider');
        $this->addSql('DROP TABLE service_provider');
        $this->addSql('DROP TABLE setting');
    }
}
