<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214113051 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_converter (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, configuration CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, converter_id INTEGER DEFAULT NULL, command VARCHAR(255) NOT NULL COLLATE BINARY, stage VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, has_stop_on_error BOOLEAN NOT NULL, tags CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , CONSTRAINT FK_95DDEAADD10938F7 FOREIGN KEY (converter_id) REFERENCES sdk_converter (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95DDEAADD10938F7 ON sdk_command (converter_id)');
        $this->addSql('CREATE TABLE sdk_file (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL COLLATE BINARY, content VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DAF6F63B548B0F ON sdk_file (path)');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL, CONSTRAINT FK_ABFCC59E5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE TABLE sdk_placeholder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, value_resolver VARCHAR(255) NOT NULL COLLATE BINARY, configuration CLOB NOT NULL --(DC2Type:json)
        , is_optional BOOLEAN NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_removed_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE sdk_removed_events_commands (removed_event_id INTEGER NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, command_id), CONSTRAINT FK_4A58221C5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id)  ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_4A58221C33E1689A FOREIGN KEY (command_id) REFERENCES sdk_command (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4A58221C5CA6D57F ON sdk_removed_events_commands (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A58221C33E1689A ON sdk_removed_events_commands (command_id)');
        $this->addSql('CREATE TABLE sdk_removed_events_placeholders (removed_event_id INTEGER NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, placeholder_id), CONSTRAINT FK_9503214B5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id)  ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9503214BDA75C033 FOREIGN KEY (placeholder_id) REFERENCES sdk_placeholder (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9503214B5CA6D57F ON sdk_removed_events_placeholders (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9503214BDA75C033 ON sdk_removed_events_placeholders (placeholder_id)');
        $this->addSql('CREATE TABLE sdk_removed_events_files (removed_event_id INTEGER NOT NULL, file_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, file_id), CONSTRAINT FK_10A392175CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id)  ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_10A3921793CB796C FOREIGN KEY (file_id) REFERENCES sdk_file (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_10A392175CA6D57F ON sdk_removed_events_files (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10A3921793CB796C ON sdk_removed_events_files (file_id)');
        $this->addSql('CREATE TABLE sdk_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL COLLATE BINARY, "values" CLOB DEFAULT NULL --(DC2Type:json)
        , strategy VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, is_project BOOLEAN NOT NULL, has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL COLLATE BINARY, initializer VARCHAR(255) DEFAULT NULL COLLATE BINARY)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL COLLATE BINARY, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL COLLATE BINARY, help VARCHAR(255) DEFAULT NULL COLLATE BINARY, version VARCHAR(255) NOT NULL COLLATE BINARY, successor VARCHAR(255) DEFAULT NULL COLLATE BINARY, is_deprecated BOOLEAN NOT NULL, stages CLOB NOT NULL COLLATE BINARY, stage VARCHAR(255) NOT NULL COLLATE BINARY, optional BOOLEAN NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_934E8256D7D7318C FOREIGN KEY (lifecycle_id) REFERENCES sdk_lifecycle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
        $this->addSql('CREATE TABLE sdk_tasks_commands (task_id VARCHAR(255) NOT NULL COLLATE BINARY, command_id INTEGER NOT NULL, PRIMARY KEY(task_id, command_id), CONSTRAINT FK_B29D76B78DB60186 FOREIGN KEY (task_id) REFERENCES sdk_task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B29D76B733E1689A FOREIGN KEY (command_id) REFERENCES sdk_command (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B29D76B78DB60186 ON sdk_tasks_commands (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B29D76B733E1689A ON sdk_tasks_commands (command_id)');
        $this->addSql('CREATE TABLE sdk_tasks_placeholders (task_id VARCHAR(255) NOT NULL COLLATE BINARY, placeholder_id INTEGER NOT NULL, PRIMARY KEY(task_id, placeholder_id), CONSTRAINT FK_613760068DB60186 FOREIGN KEY (task_id) REFERENCES sdk_task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_61376006DA75C033 FOREIGN KEY (placeholder_id) REFERENCES sdk_placeholder (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_613760068DB60186 ON sdk_tasks_placeholders (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61376006DA75C033 ON sdk_tasks_placeholders (placeholder_id)');
        $this->addSql('CREATE TABLE sdk_workflow (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project VARCHAR(255) NOT NULL COLLATE BINARY, status CLOB NOT NULL --(DC2Type:json)
        , workflow VARCHAR(255) NOT NULL COLLATE BINARY)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4C07468E2FB3D0EE ON sdk_workflow (project, workflow)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_converter');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('DROP TABLE sdk_file');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_placeholder');
        $this->addSql('DROP TABLE sdk_removed_event');
        $this->addSql('DROP TABLE sdk_removed_events_commands');
        $this->addSql('DROP TABLE sdk_removed_events_files');
        $this->addSql('DROP TABLE sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE sdk_setting');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('DROP TABLE sdk_tasks_commands');
        $this->addSql('DROP TABLE sdk_tasks_placeholders');
        $this->addSql('DROP TABLE sdk_workflow');
    }
}
