<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220614195816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_workflow_transition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, workflow_id INTEGER DEFAULT NULL, status CLOB NOT NULL --(DC2Type:json)
        , transition VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        , time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
        $this->addSql('CREATE INDEX IDX_A6C872672C7C2CBA ON sdk_workflow_transition (workflow_id)');
        $this->addSql('DROP INDEX UNIQ_4C07468E2FB3D0EE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow AS SELECT id, project, status, workflow FROM sdk_workflow');
        $this->addSql('DROP TABLE sdk_workflow');
        $this->addSql('CREATE TABLE sdk_workflow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, project VARCHAR(255) NOT NULL, status CLOB NOT NULL --(DC2Type:json)
        , workflow VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, CONSTRAINT FK_4C07468E727ACA70 FOREIGN KEY (parent_id) REFERENCES sdk_workflow (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_workflow (id, project, status, workflow, code) SELECT id, project, status, workflow, workflow FROM __temp__sdk_workflow');
        $this->addSql('DROP TABLE __temp__sdk_workflow');
        $this->addSql('CREATE INDEX IDX_4C07468E727ACA70 ON sdk_workflow (parent_id)');
        $this->addSql('CREATE UNIQUE INDEX event_user ON sdk_workflow (project, code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_workflow_transition');
        $this->addSql('DROP INDEX IDX_4C07468E727ACA70');
        $this->addSql('DROP INDEX event_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow AS SELECT id, project, workflow, status FROM sdk_workflow');
        $this->addSql('DROP TABLE sdk_workflow');
        $this->addSql('CREATE TABLE sdk_workflow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project VARCHAR(255) NOT NULL, workflow VARCHAR(255) NOT NULL, status CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO sdk_workflow (id, project, workflow, status) SELECT id, project, workflow, status FROM __temp__sdk_workflow');
        $this->addSql('DROP TABLE __temp__sdk_workflow');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C07468E2FB3D0EE ON sdk_workflow (project, workflow)');
    }
}
