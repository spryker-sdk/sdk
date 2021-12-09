<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211208063215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sdk_setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sdk_setting (id INT NOT NULL, path VARCHAR(255) NOT NULL, "values" JSON DEFAULT NULL, strategy VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, is_project BOOLEAN NOT NULL, has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE sdk_setting_id_seq CASCADE');
        $this->addSql('DROP TABLE sdk_setting');
    }
}
