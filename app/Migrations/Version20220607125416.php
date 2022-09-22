<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220607125416 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Added error message field to the sdk_command table.';
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_95DDEAADD10938F7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_command AS SELECT id, converter_id, command, stage, type, has_stop_on_error, tags FROM sdk_command');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, converter_id INTEGER DEFAULT NULL, command VARCHAR(255) NOT NULL, stage VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL, tags CLOB NOT NULL --(DC2Type:json)
        , error_message VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_95DDEAADD10938F7 FOREIGN KEY (converter_id) REFERENCES sdk_converter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_command (id, converter_id, command, stage, type, has_stop_on_error, tags) SELECT id, converter_id, command, stage, type, has_stop_on_error, tags FROM __temp__sdk_command');
        $this->addSql('DROP TABLE __temp__sdk_command');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95DDEAADD10938F7 ON sdk_command (converter_id)');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP INDEX UNIQ_95DDEAADD10938F7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_command AS SELECT id, converter_id, command, type, has_stop_on_error, tags, stage FROM sdk_command');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, converter_id INTEGER DEFAULT NULL, command VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL, tags CLOB NOT NULL --(DC2Type:json)
        , stage VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO sdk_command (id, converter_id, command, type, has_stop_on_error, tags, stage) SELECT id, converter_id, command, type, has_stop_on_error, tags, stage FROM __temp__sdk_command');
        $this->addSql('DROP TABLE __temp__sdk_command');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95DDEAADD10938F7 ON sdk_command (converter_id)');
    }
}
