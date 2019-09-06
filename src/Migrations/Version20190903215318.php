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
