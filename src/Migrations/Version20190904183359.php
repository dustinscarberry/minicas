<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190904183359 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX tracking_id_idx ON authenticated_session');
        $this->addSql('ALTER TABLE authenticated_session ADD hash_id VARCHAR(25) NOT NULL');
        $this->addSql('CREATE INDEX tracking_id_idx ON authenticated_session (tracking_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX tracking_id_idx ON authenticated_session');
        $this->addSql('ALTER TABLE authenticated_session DROP hash_id');
        $this->addSql('CREATE INDEX tracking_id_idx ON authenticated_session (tracking_id(191))');
    }
}
