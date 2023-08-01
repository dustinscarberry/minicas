<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230726163936 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX tracking_id_idx ON authenticated_session (tracking_id)');
        $this->addSql('ALTER TABLE user ADD last_login INT NOT NULL, ADD last_failed_login INT DEFAULT NULL, ADD failed_login_count INT DEFAULT NULL');
        $this->addSql('ALTER TABLE sessions CHANGE sess_data sess_data LONGBLOB NOT NULL');
        $this->addSql('DROP INDEX sessions_sess_lifetime_idx ON sessions');
        $this->addSql('CREATE INDEX sess_lifetime_idx ON sessions (sess_lifetime)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions CHANGE sess_data sess_data BLOB NOT NULL');
        $this->addSql('DROP INDEX sess_lifetime_idx ON sessions');
        $this->addSql('CREATE INDEX sessions_sess_lifetime_idx ON sessions (sess_lifetime)');
        $this->addSql('DROP INDEX tracking_id_idx ON authenticated_session');
        $this->addSql('ALTER TABLE user DROP last_login, DROP last_failed_login, DROP failed_login_count');
    }
}
