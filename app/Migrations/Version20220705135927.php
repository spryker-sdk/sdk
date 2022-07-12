<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705135927 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_telemetry_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, version SMALLINT NOT NULL, scope VARCHAR(64) NOT NULL, payload CLOB NOT NULL --(DC2Type:object)
        , metadata CLOB NOT NULL --(DC2Type:object)
        , synchronization_attempts_count SMALLINT NOT NULL, last_synchronisation_timestamp INTEGER UNSIGNED DEFAULT NULL, triggered_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE sdk_lock (key_id VARCHAR(64) NOT NULL COLLATE BINARY, key_token VARCHAR(44) NOT NULL COLLATE BINARY, key_expiration INTEGER UNSIGNED NOT NULL, PRIMARY KEY(key_id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_telemetry_event');
        $this->addSql('DROP TABLE lock_keys');
    }
}
