<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220727133310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Tables names fixes.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sdk_removed_event_command (removed_event_id INTEGER NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, command_id))');
        $this->addSql('CREATE INDEX IDX_4852800B5CA6D57F ON sdk_removed_event_command (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4852800B33E1689A ON sdk_removed_event_command (command_id)');
        $this->addSql('CREATE TABLE sdk_removed_event_placeholder (removed_event_id INTEGER NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, placeholder_id))');
        $this->addSql('CREATE INDEX IDX_50E1FAC5CA6D57F ON sdk_removed_event_placeholder (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_50E1FACDA75C033 ON sdk_removed_event_placeholder (placeholder_id)');
        $this->addSql('CREATE TABLE sdk_removed_event_file (removed_event_id INTEGER NOT NULL, file_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, file_id))');
        $this->addSql('CREATE INDEX IDX_D7F351F15CA6D57F ON sdk_removed_event_file (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D7F351F193CB796C ON sdk_removed_event_file (file_id)');
        $this->addSql('CREATE TABLE sdk_task_command (task_id VARCHAR(255) NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(task_id, command_id))');
        $this->addSql('CREATE INDEX IDX_D5B63CEF8DB60186 ON sdk_task_command (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D5B63CEF33E1689A ON sdk_task_command (command_id)');
        $this->addSql('CREATE TABLE sdk_task_placeholder (task_id VARCHAR(255) NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(task_id, placeholder_id))');
        $this->addSql('CREATE INDEX IDX_24FB17A68DB60186 ON sdk_task_placeholder (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_24FB17A6DA75C033 ON sdk_task_placeholder (placeholder_id)');
        $this->addSql('DROP TABLE sdk_removed_events_commands');
        $this->addSql('DROP TABLE sdk_removed_events_files');
        $this->addSql('DROP TABLE sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE sdk_tasks_commands');
        $this->addSql('DROP TABLE sdk_tasks_placeholders');
        $this->addSql('DROP INDEX UNIQ_ABFCC59E5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_lifecycle AS SELECT id, removed_event_id FROM sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL, CONSTRAINT FK_ABFCC59E5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_lifecycle (id, removed_event_id) SELECT id, removed_event_id FROM __temp__sdk_lifecycle');
        $this->addSql('DROP TABLE __temp__sdk_lifecycle');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('DROP INDEX UNIQ_934E8256D7D7318C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_task AS SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM sdk_task');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, version VARCHAR(255) NOT NULL, successor VARCHAR(255) DEFAULT NULL, is_deprecated BOOLEAN NOT NULL, stages CLOB NOT NULL --(DC2Type:json)
        , stage VARCHAR(255) NOT NULL, optional BOOLEAN DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_934E8256D7D7318C FOREIGN KEY (lifecycle_id) REFERENCES sdk_lifecycle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_task (id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional) SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM __temp__sdk_task');
        $this->addSql('DROP TABLE __temp__sdk_task');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
        $this->addSql('DROP INDEX event_user');
        $this->addSql('DROP INDEX IDX_4C07468E727ACA70');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow AS SELECT id, parent_id, project, status, workflow, code FROM sdk_workflow');
        $this->addSql('DROP TABLE sdk_workflow');
        $this->addSql('CREATE TABLE sdk_workflow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, project VARCHAR(255) NOT NULL, status CLOB NOT NULL --(DC2Type:json)
        , workflow VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, CONSTRAINT FK_4C07468E727ACA70 FOREIGN KEY (parent_id) REFERENCES sdk_workflow (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_workflow (id, parent_id, project, status, workflow, code) SELECT id, parent_id, project, status, workflow, code FROM __temp__sdk_workflow');
        $this->addSql('DROP TABLE __temp__sdk_workflow');
        $this->addSql('CREATE UNIQUE INDEX event_user ON sdk_workflow (project, code)');
        $this->addSql('CREATE INDEX IDX_4C07468E727ACA70 ON sdk_workflow (parent_id)');
        $this->addSql('DROP INDEX IDX_383E84372C7C2CBA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow_transition AS SELECT id, workflow_id, status, transition, state, data, time FROM sdk_workflow_transition');
        $this->addSql('DROP TABLE sdk_workflow_transition');
        $this->addSql('CREATE TABLE sdk_workflow_transition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, workflow_id INTEGER DEFAULT NULL, status CLOB NOT NULL --(DC2Type:json)
        , transition VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        , time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, CONSTRAINT FK_383E84372C7C2CBA FOREIGN KEY (workflow_id) REFERENCES sdk_workflow (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_workflow_transition (id, workflow_id, status, transition, state, data, time) SELECT id, workflow_id, status, transition, state, data, time FROM __temp__sdk_workflow_transition');
        $this->addSql('DROP TABLE __temp__sdk_workflow_transition');
        $this->addSql('CREATE INDEX IDX_383E84372C7C2CBA ON sdk_workflow_transition (workflow_id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sdk_removed_events_commands (removed_event_id INTEGER NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, command_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A58221C33E1689A ON sdk_removed_events_commands (command_id)');
        $this->addSql('CREATE INDEX IDX_4A58221C5CA6D57F ON sdk_removed_events_commands (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_removed_events_files (removed_event_id INTEGER NOT NULL, file_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, file_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10A3921793CB796C ON sdk_removed_events_files (file_id)');
        $this->addSql('CREATE INDEX IDX_10A392175CA6D57F ON sdk_removed_events_files (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_removed_events_placeholders (removed_event_id INTEGER NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, placeholder_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9503214BDA75C033 ON sdk_removed_events_placeholders (placeholder_id)');
        $this->addSql('CREATE INDEX IDX_9503214B5CA6D57F ON sdk_removed_events_placeholders (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_tasks_commands (task_id VARCHAR(255) NOT NULL COLLATE BINARY, command_id INTEGER NOT NULL, PRIMARY KEY(task_id, command_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B29D76B733E1689A ON sdk_tasks_commands (command_id)');
        $this->addSql('CREATE INDEX IDX_B29D76B78DB60186 ON sdk_tasks_commands (task_id)');
        $this->addSql('CREATE TABLE sdk_tasks_placeholders (task_id VARCHAR(255) NOT NULL COLLATE BINARY, placeholder_id INTEGER NOT NULL, PRIMARY KEY(task_id, placeholder_id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61376006DA75C033 ON sdk_tasks_placeholders (placeholder_id)');
        $this->addSql('CREATE INDEX IDX_613760068DB60186 ON sdk_tasks_placeholders (task_id)');
        $this->addSql('DROP TABLE sdk_removed_event_command');
        $this->addSql('DROP TABLE sdk_removed_event_placeholder');
        $this->addSql('DROP TABLE sdk_removed_event_file');
        $this->addSql('DROP TABLE sdk_task_command');
        $this->addSql('DROP TABLE sdk_task_placeholder');
        $this->addSql('DROP INDEX UNIQ_ABFCC59E5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_lifecycle AS SELECT id, removed_event_id FROM sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO sdk_lifecycle (id, removed_event_id) SELECT id, removed_event_id FROM __temp__sdk_lifecycle');
        $this->addSql('DROP TABLE __temp__sdk_lifecycle');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('DROP INDEX UNIQ_934E8256D7D7318C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_task AS SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM sdk_task');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, version VARCHAR(255) NOT NULL, successor VARCHAR(255) DEFAULT NULL, is_deprecated BOOLEAN NOT NULL, stages CLOB NOT NULL, stage VARCHAR(255) NOT NULL, optional BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO sdk_task (id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional) SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM __temp__sdk_task');
        $this->addSql('DROP TABLE __temp__sdk_task');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
        $this->addSql('DROP INDEX IDX_4C07468E727ACA70');
        $this->addSql('DROP INDEX event_user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow AS SELECT id, parent_id, project, workflow, code, status FROM sdk_workflow');
        $this->addSql('DROP TABLE sdk_workflow');
        $this->addSql('CREATE TABLE sdk_workflow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, parent_id INTEGER DEFAULT NULL, project VARCHAR(255) NOT NULL, workflow VARCHAR(255) NOT NULL, code VARCHAR(255) NOT NULL, status CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO sdk_workflow (id, parent_id, project, workflow, code, status) SELECT id, parent_id, project, workflow, code, status FROM __temp__sdk_workflow');
        $this->addSql('DROP TABLE __temp__sdk_workflow');
        $this->addSql('CREATE INDEX IDX_4C07468E727ACA70 ON sdk_workflow (parent_id)');
        $this->addSql('CREATE UNIQUE INDEX event_user ON sdk_workflow (project, code)');
        $this->addSql('DROP INDEX IDX_383E84372C7C2CBA');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow_transition AS SELECT id, workflow_id, status, transition, state, data, time FROM sdk_workflow_transition');
        $this->addSql('DROP TABLE sdk_workflow_transition');
        $this->addSql('CREATE TABLE sdk_workflow_transition (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, workflow_id INTEGER DEFAULT NULL, status CLOB NOT NULL --(DC2Type:json)
        , transition VARCHAR(255) NOT NULL, state VARCHAR(255) NOT NULL, data CLOB NOT NULL --(DC2Type:json)
        , time DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL)');
        $this->addSql('INSERT INTO sdk_workflow_transition (id, workflow_id, status, transition, state, data, time) SELECT id, workflow_id, status, transition, state, data, time FROM __temp__sdk_workflow_transition');
        $this->addSql('DROP TABLE __temp__sdk_workflow_transition');
        $this->addSql('CREATE INDEX IDX_383E84372C7C2CBA ON sdk_workflow_transition (workflow_id)');
    }
}
