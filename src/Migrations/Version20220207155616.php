<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220207155616 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authenticated_service DROP FOREIGN KEY FK_80236C47613FECDF');
        $this->addSql('ALTER TABLE authenticated_service ADD CONSTRAINT FK_80236C47613FECDF FOREIGN KEY (session_id) REFERENCES authenticated_session (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE cas_ticket DROP FOREIGN KEY FK_436241CFED5CA9E6');
        $this->addSql('ALTER TABLE cas_ticket ADD CONSTRAINT FK_436241CFED5CA9E6 FOREIGN KEY (service_id) REFERENCES authenticated_service (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE authenticated_service DROP FOREIGN KEY FK_80236C47613FECDF');
        $this->addSql('ALTER TABLE authenticated_service ADD CONSTRAINT FK_80236C47613FECDF FOREIGN KEY (session_id) REFERENCES authenticated_session (id)');
        $this->addSql('ALTER TABLE cas_ticket DROP FOREIGN KEY FK_436241CFED5CA9E6');
        $this->addSql('ALTER TABLE cas_ticket ADD CONSTRAINT FK_436241CFED5CA9E6 FOREIGN KEY (service_id) REFERENCES authenticated_service (id)');
    }
}
