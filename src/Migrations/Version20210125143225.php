<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125143225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE service_category (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, created INT NOT NULL, updated INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE service_provider ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE service_provider ADD CONSTRAINT FK_6BB228A112469DE2 FOREIGN KEY (category_id) REFERENCES service_category (id)');
        $this->addSql('CREATE INDEX IDX_6BB228A112469DE2 ON service_provider (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE service_provider DROP FOREIGN KEY FK_6BB228A112469DE2');
        $this->addSql('DROP TABLE service_category');
        $this->addSql('DROP INDEX IDX_6BB228A112469DE2 ON service_provider');
        $this->addSql('ALTER TABLE service_provider DROP category_id');
    }
}
