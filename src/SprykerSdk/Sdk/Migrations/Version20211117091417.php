<?php

declare(strict_types=1);

namespace SprykerSdk\Sdk\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211117091417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, "values" CLOB DEFAULT NULL --(DC2Type:json)
        , strategy VARCHAR(255) NOT NULL, is_project BOOLEAN NOT NULL, has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_setting');
    }
}
