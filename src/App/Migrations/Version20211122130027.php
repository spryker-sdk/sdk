<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211122130027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id VARCHAR(255) DEFAULT NULL, command VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_95DDEAAD8DB60186 ON sdk_command (task_id)');
        $this->addSql('CREATE TABLE sdk_placeholder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, value_resolver VARCHAR(255) NOT NULL, configuration CLOB NOT NULL --(DC2Type:json)
        , is_optional BOOLEAN NOT NULL)');
        $this->addSql('CREATE INDEX IDX_6C373D6E8DB60186 ON sdk_placeholder (task_id)');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('DROP TABLE sdk_placeholder');
        $this->addSql('DROP TABLE sdk_task');
    }
}
