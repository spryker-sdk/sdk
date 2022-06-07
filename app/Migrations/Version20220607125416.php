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
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_95DDEAADD10938F7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_command AS SELECT id, converter_id, command, stage, type, has_stop_on_error, tags FROM sdk_command');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, converter_id INTEGER DEFAULT NULL, command VARCHAR(255) NOT NULL, stage VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL, tags CLOB NOT NULL --(DC2Type:json)
        , error_message VARCHAR(255) NOT NULL, CONSTRAINT FK_95DDEAADD10938F7 FOREIGN KEY (converter_id) REFERENCES sdk_converter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_command (id, converter_id, command, stage, type, has_stop_on_error, tags) SELECT id, converter_id, command, stage, type, has_stop_on_error, tags FROM __temp__sdk_command');
        $this->addSql('DROP TABLE __temp__sdk_command');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95DDEAADD10938F7 ON sdk_command (converter_id)');
        $this->addSql('DROP INDEX UNIQ_ABFCC59E5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_lifecycle AS SELECT id, removed_event_id FROM sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL, CONSTRAINT FK_ABFCC59E5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_lifecycle (id, removed_event_id) SELECT id, removed_event_id FROM __temp__sdk_lifecycle');
        $this->addSql('DROP TABLE __temp__sdk_lifecycle');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('DROP INDEX UNIQ_4A58221C33E1689A');
        $this->addSql('DROP INDEX IDX_4A58221C5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_removed_events_commands AS SELECT removed_event_id, command_id FROM sdk_removed_events_commands');
        $this->addSql('DROP TABLE sdk_removed_events_commands');
        $this->addSql('CREATE TABLE sdk_removed_events_commands (removed_event_id INTEGER NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, command_id), CONSTRAINT FK_4A58221C5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4A58221C33E1689A FOREIGN KEY (command_id) REFERENCES sdk_command (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_removed_events_commands (removed_event_id, command_id) SELECT removed_event_id, command_id FROM __temp__sdk_removed_events_commands');
        $this->addSql('DROP TABLE __temp__sdk_removed_events_commands');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A58221C33E1689A ON sdk_removed_events_commands (command_id)');
        $this->addSql('CREATE INDEX IDX_4A58221C5CA6D57F ON sdk_removed_events_commands (removed_event_id)');
        $this->addSql('DROP INDEX UNIQ_9503214BDA75C033');
        $this->addSql('DROP INDEX IDX_9503214B5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_removed_events_placeholders AS SELECT removed_event_id, placeholder_id FROM sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE sdk_removed_events_placeholders');
        $this->addSql('CREATE TABLE sdk_removed_events_placeholders (removed_event_id INTEGER NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, placeholder_id), CONSTRAINT FK_9503214B5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9503214BDA75C033 FOREIGN KEY (placeholder_id) REFERENCES sdk_placeholder (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_removed_events_placeholders (removed_event_id, placeholder_id) SELECT removed_event_id, placeholder_id FROM __temp__sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE __temp__sdk_removed_events_placeholders');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9503214BDA75C033 ON sdk_removed_events_placeholders (placeholder_id)');
        $this->addSql('CREATE INDEX IDX_9503214B5CA6D57F ON sdk_removed_events_placeholders (removed_event_id)');
        $this->addSql('DROP INDEX UNIQ_10A3921793CB796C');
        $this->addSql('DROP INDEX IDX_10A392175CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_removed_events_files AS SELECT removed_event_id, file_id FROM sdk_removed_events_files');
        $this->addSql('DROP TABLE sdk_removed_events_files');
        $this->addSql('CREATE TABLE sdk_removed_events_files (removed_event_id INTEGER NOT NULL, file_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, file_id), CONSTRAINT FK_10A392175CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_10A3921793CB796C FOREIGN KEY (file_id) REFERENCES sdk_file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_removed_events_files (removed_event_id, file_id) SELECT removed_event_id, file_id FROM __temp__sdk_removed_events_files');
        $this->addSql('DROP TABLE __temp__sdk_removed_events_files');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10A3921793CB796C ON sdk_removed_events_files (file_id)');
        $this->addSql('CREATE INDEX IDX_10A392175CA6D57F ON sdk_removed_events_files (removed_event_id)');
        $this->addSql('DROP INDEX UNIQ_934E8256D7D7318C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_task AS SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM sdk_task');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, version VARCHAR(255) NOT NULL, successor VARCHAR(255) DEFAULT NULL, is_deprecated BOOLEAN NOT NULL, stages CLOB NOT NULL --(DC2Type:json)
        , stage VARCHAR(255) NOT NULL, optional BOOLEAN DEFAULT NULL, PRIMARY KEY(id), CONSTRAINT FK_934E8256D7D7318C FOREIGN KEY (lifecycle_id) REFERENCES sdk_lifecycle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_task (id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional) SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM __temp__sdk_task');
        $this->addSql('DROP TABLE __temp__sdk_task');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
        $this->addSql('DROP INDEX UNIQ_B29D76B733E1689A');
        $this->addSql('DROP INDEX IDX_B29D76B78DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_tasks_commands AS SELECT task_id, command_id FROM sdk_tasks_commands');
        $this->addSql('DROP TABLE sdk_tasks_commands');
        $this->addSql('CREATE TABLE sdk_tasks_commands (task_id VARCHAR(255) NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(task_id, command_id), CONSTRAINT FK_B29D76B78DB60186 FOREIGN KEY (task_id) REFERENCES sdk_task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B29D76B733E1689A FOREIGN KEY (command_id) REFERENCES sdk_command (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_tasks_commands (task_id, command_id) SELECT task_id, command_id FROM __temp__sdk_tasks_commands');
        $this->addSql('DROP TABLE __temp__sdk_tasks_commands');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B29D76B733E1689A ON sdk_tasks_commands (command_id)');
        $this->addSql('CREATE INDEX IDX_B29D76B78DB60186 ON sdk_tasks_commands (task_id)');
        $this->addSql('DROP INDEX UNIQ_61376006DA75C033');
        $this->addSql('DROP INDEX IDX_613760068DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_tasks_placeholders AS SELECT task_id, placeholder_id FROM sdk_tasks_placeholders');
        $this->addSql('DROP TABLE sdk_tasks_placeholders');
        $this->addSql('CREATE TABLE sdk_tasks_placeholders (task_id VARCHAR(255) NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(task_id, placeholder_id), CONSTRAINT FK_613760068DB60186 FOREIGN KEY (task_id) REFERENCES sdk_task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_61376006DA75C033 FOREIGN KEY (placeholder_id) REFERENCES sdk_placeholder (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_tasks_placeholders (task_id, placeholder_id) SELECT task_id, placeholder_id FROM __temp__sdk_tasks_placeholders');
        $this->addSql('DROP TABLE __temp__sdk_tasks_placeholders');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61376006DA75C033 ON sdk_tasks_placeholders (placeholder_id)');
        $this->addSql('CREATE INDEX IDX_613760068DB60186 ON sdk_tasks_placeholders (task_id)');
        $this->addSql('DROP INDEX UNIQ_4C07468E2FB3D0EE');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_workflow AS SELECT id, project, status, workflow FROM sdk_workflow');
        $this->addSql('DROP TABLE sdk_workflow');
        $this->addSql('CREATE TABLE sdk_workflow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project VARCHAR(255) NOT NULL, status CLOB NOT NULL --(DC2Type:json)
        , workflow VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO sdk_workflow (id, project, status, workflow) SELECT id, project, status, workflow FROM __temp__sdk_workflow');
        $this->addSql('DROP TABLE __temp__sdk_workflow');
        $this->addSql('CREATE UNIQUE INDEX event_user ON sdk_workflow (project, workflow)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_95DDEAADD10938F7');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_command AS SELECT id, converter_id, command, type, has_stop_on_error, tags, stage FROM sdk_command');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, converter_id INTEGER DEFAULT NULL, command VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL, tags CLOB NOT NULL --(DC2Type:json)
        , stage VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO sdk_command (id, converter_id, command, type, has_stop_on_error, tags, stage) SELECT id, converter_id, command, type, has_stop_on_error, tags, stage FROM __temp__sdk_command');
        $this->addSql('DROP TABLE __temp__sdk_command');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95DDEAADD10938F7 ON sdk_command (converter_id)');
        $this->addSql('DROP INDEX UNIQ_ABFCC59E5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_lifecycle AS SELECT id, removed_event_id FROM sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO sdk_lifecycle (id, removed_event_id) SELECT id, removed_event_id FROM __temp__sdk_lifecycle');
        $this->addSql('DROP TABLE __temp__sdk_lifecycle');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('DROP INDEX IDX_4A58221C5CA6D57F');
        $this->addSql('DROP INDEX UNIQ_4A58221C33E1689A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_removed_events_commands AS SELECT removed_event_id, command_id FROM sdk_removed_events_commands');
        $this->addSql('DROP TABLE sdk_removed_events_commands');
        $this->addSql('CREATE TABLE sdk_removed_events_commands (removed_event_id INTEGER NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, command_id))');
        $this->addSql('INSERT INTO sdk_removed_events_commands (removed_event_id, command_id) SELECT removed_event_id, command_id FROM __temp__sdk_removed_events_commands');
        $this->addSql('DROP TABLE __temp__sdk_removed_events_commands');
        $this->addSql('CREATE INDEX IDX_4A58221C5CA6D57F ON sdk_removed_events_commands (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A58221C33E1689A ON sdk_removed_events_commands (command_id)');
        $this->addSql('DROP INDEX IDX_10A392175CA6D57F');
        $this->addSql('DROP INDEX UNIQ_10A3921793CB796C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_removed_events_files AS SELECT removed_event_id, file_id FROM sdk_removed_events_files');
        $this->addSql('DROP TABLE sdk_removed_events_files');
        $this->addSql('CREATE TABLE sdk_removed_events_files (removed_event_id INTEGER NOT NULL, file_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, file_id))');
        $this->addSql('INSERT INTO sdk_removed_events_files (removed_event_id, file_id) SELECT removed_event_id, file_id FROM __temp__sdk_removed_events_files');
        $this->addSql('DROP TABLE __temp__sdk_removed_events_files');
        $this->addSql('CREATE INDEX IDX_10A392175CA6D57F ON sdk_removed_events_files (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10A3921793CB796C ON sdk_removed_events_files (file_id)');
        $this->addSql('DROP INDEX IDX_9503214B5CA6D57F');
        $this->addSql('DROP INDEX UNIQ_9503214BDA75C033');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_removed_events_placeholders AS SELECT removed_event_id, placeholder_id FROM sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE sdk_removed_events_placeholders');
        $this->addSql('CREATE TABLE sdk_removed_events_placeholders (removed_event_id INTEGER NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, placeholder_id))');
        $this->addSql('INSERT INTO sdk_removed_events_placeholders (removed_event_id, placeholder_id) SELECT removed_event_id, placeholder_id FROM __temp__sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE __temp__sdk_removed_events_placeholders');
        $this->addSql('CREATE INDEX IDX_9503214B5CA6D57F ON sdk_removed_events_placeholders (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9503214BDA75C033 ON sdk_removed_events_placeholders (placeholder_id)');
        $this->addSql('DROP INDEX UNIQ_934E8256D7D7318C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_task AS SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM sdk_task');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, version VARCHAR(255) NOT NULL, successor VARCHAR(255) DEFAULT NULL, is_deprecated BOOLEAN NOT NULL, stages CLOB NOT NULL, stage VARCHAR(255) NOT NULL, optional BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO sdk_task (id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional) SELECT id, lifecycle_id, short_description, help, version, successor, is_deprecated, stages, stage, optional FROM __temp__sdk_task');
        $this->addSql('DROP TABLE __temp__sdk_task');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
        $this->addSql('DROP INDEX IDX_B29D76B78DB60186');
        $this->addSql('DROP INDEX UNIQ_B29D76B733E1689A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_tasks_commands AS SELECT task_id, command_id FROM sdk_tasks_commands');
        $this->addSql('DROP TABLE sdk_tasks_commands');
        $this->addSql('CREATE TABLE sdk_tasks_commands (task_id VARCHAR(255) NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(task_id, command_id))');
        $this->addSql('INSERT INTO sdk_tasks_commands (task_id, command_id) SELECT task_id, command_id FROM __temp__sdk_tasks_commands');
        $this->addSql('DROP TABLE __temp__sdk_tasks_commands');
        $this->addSql('CREATE INDEX IDX_B29D76B78DB60186 ON sdk_tasks_commands (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B29D76B733E1689A ON sdk_tasks_commands (command_id)');
        $this->addSql('DROP INDEX IDX_613760068DB60186');
        $this->addSql('DROP INDEX UNIQ_61376006DA75C033');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_tasks_placeholders AS SELECT task_id, placeholder_id FROM sdk_tasks_placeholders');
        $this->addSql('DROP TABLE sdk_tasks_placeholders');
        $this->addSql('CREATE TABLE sdk_tasks_placeholders (task_id VARCHAR(255) NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(task_id, placeholder_id))');
        $this->addSql('INSERT INTO sdk_tasks_placeholders (task_id, placeholder_id) SELECT task_id, placeholder_id FROM __temp__sdk_tasks_placeholders');
        $this->addSql('DROP TABLE __temp__sdk_tasks_placeholders');
        $this->addSql('CREATE INDEX IDX_613760068DB60186 ON sdk_tasks_placeholders (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61376006DA75C033 ON sdk_tasks_placeholders (placeholder_id)');
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
