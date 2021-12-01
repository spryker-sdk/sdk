<?php

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201132057 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, command VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE sdk_file (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DAF6F63B548B0F ON sdk_file (path)');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_placeholder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, value_resolver VARCHAR(255) NOT NULL, configuration CLOB NOT NULL --(DC2Type:json)
        , is_optional BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE sdk_removed_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('CREATE TABLE sdk_removed_events_commands (removed_event_id INTEGER NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, command_id))');
        $this->addSql('CREATE INDEX IDX_4A58221C5CA6D57F ON sdk_removed_events_commands (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4A58221C33E1689A ON sdk_removed_events_commands (command_id)');
        $this->addSql('CREATE TABLE sdk_removed_events_placeholders (removed_event_id INTEGER NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, placeholder_id))');
        $this->addSql('CREATE INDEX IDX_9503214B5CA6D57F ON sdk_removed_events_placeholders (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9503214BDA75C033 ON sdk_removed_events_placeholders (placeholder_id)');
        $this->addSql('CREATE TABLE sdk_removed_events_files (removed_event_id INTEGER NOT NULL, file_id INTEGER NOT NULL, PRIMARY KEY(removed_event_id, file_id))');
        $this->addSql('CREATE INDEX IDX_10A392175CA6D57F ON sdk_removed_events_files (removed_event_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_10A3921793CB796C ON sdk_removed_events_files (file_id)');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, version VARCHAR(255) NOT NULL, successor VARCHAR(255) DEFAULT NULL, is_deprecated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
        $this->addSql('CREATE TABLE sdk_tasks_commands (task_id VARCHAR(255) NOT NULL, command_id INTEGER NOT NULL, PRIMARY KEY(task_id, command_id))');
        $this->addSql('CREATE INDEX IDX_B29D76B78DB60186 ON sdk_tasks_commands (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B29D76B733E1689A ON sdk_tasks_commands (command_id)');
        $this->addSql('CREATE TABLE sdk_tasks_placeholders (task_id VARCHAR(255) NOT NULL, placeholder_id INTEGER NOT NULL, PRIMARY KEY(task_id, placeholder_id))');
        $this->addSql('CREATE INDEX IDX_613760068DB60186 ON sdk_tasks_placeholders (task_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_61376006DA75C033 ON sdk_tasks_placeholders (placeholder_id)');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('DROP TABLE sdk_file');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_placeholder');
        $this->addSql('DROP TABLE sdk_removed_event');
        $this->addSql('DROP TABLE sdk_removed_events_commands');
        $this->addSql('DROP TABLE sdk_removed_events_placeholders');
        $this->addSql('DROP TABLE sdk_removed_events_files');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('DROP TABLE sdk_tasks_commands');
        $this->addSql('DROP TABLE sdk_tasks_placeholders');
    }
}
