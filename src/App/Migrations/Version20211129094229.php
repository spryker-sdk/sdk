<?php

declare(strict_types=1);

/**
 * Copyright © 2019-present Spryker Systems GmbH. All rights reserved.
 * Use of this software requires acceptance of the Evaluation License Agreement. See LICENSE file.
 */

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211129094229 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    /**
     * @return void
     */
    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE sdk_file (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL, path VARCHAR(255) NOT NULL, content VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4DAF6F63B548B0F ON sdk_file (path)');
        $this->addSql('CREATE INDEX IDX_4DAF6F635CA6D57F ON sdk_file (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_lifecycle (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, removed_event_id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ABFCC59E5CA6D57F ON sdk_lifecycle (removed_event_id)');
        $this->addSql('CREATE TABLE sdk_removed_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('DROP INDEX IDX_95DDEAAD8DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_command AS SELECT id, task_id, command, type, has_stop_on_error FROM sdk_command');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, removed_event_id INTEGER DEFAULT NULL, command VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, has_stop_on_error BOOLEAN NOT NULL, CONSTRAINT FK_95DDEAAD8DB60186 FOREIGN KEY (task_id) REFERENCES sdk_task (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_95DDEAAD5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_command (id, task_id, command, type, has_stop_on_error) SELECT id, task_id, command, type, has_stop_on_error FROM __temp__sdk_command');
        $this->addSql('DROP TABLE __temp__sdk_command');
        $this->addSql('CREATE INDEX IDX_95DDEAAD8DB60186 ON sdk_command (task_id)');
        $this->addSql('CREATE INDEX IDX_95DDEAAD5CA6D57F ON sdk_command (removed_event_id)');
        $this->addSql('DROP INDEX IDX_6C373D6E8DB60186');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_placeholder AS SELECT id, task_id, name, value_resolver, configuration, is_optional FROM sdk_placeholder');
        $this->addSql('DROP TABLE sdk_placeholder');
        $this->addSql('CREATE TABLE sdk_placeholder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id VARCHAR(255) DEFAULT NULL COLLATE BINARY, removed_event_id INTEGER DEFAULT NULL, name VARCHAR(255) NOT NULL COLLATE BINARY, value_resolver VARCHAR(255) NOT NULL COLLATE BINARY, configuration CLOB NOT NULL COLLATE BINARY --(DC2Type:json)
        , is_optional BOOLEAN NOT NULL, CONSTRAINT FK_6C373D6E8DB60186 FOREIGN KEY (task_id) REFERENCES sdk_task (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_6C373D6E5CA6D57F FOREIGN KEY (removed_event_id) REFERENCES sdk_removed_event (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_placeholder (id, task_id, name, value_resolver, configuration, is_optional) SELECT id, task_id, name, value_resolver, configuration, is_optional FROM __temp__sdk_placeholder');
        $this->addSql('DROP TABLE __temp__sdk_placeholder');
        $this->addSql('CREATE INDEX IDX_6C373D6E8DB60186 ON sdk_placeholder (task_id)');
        $this->addSql('CREATE INDEX IDX_6C373D6E5CA6D57F ON sdk_placeholder (removed_event_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_task AS SELECT id, short_description, help, version, successor, deprecated FROM sdk_task');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL COLLATE BINARY, lifecycle_id INTEGER DEFAULT NULL, short_description VARCHAR(255) NOT NULL COLLATE BINARY, help VARCHAR(255) DEFAULT NULL COLLATE BINARY, version VARCHAR(255) NOT NULL COLLATE BINARY, successor VARCHAR(255) DEFAULT NULL COLLATE BINARY, deprecated BOOLEAN NOT NULL, PRIMARY KEY(id), CONSTRAINT FK_934E8256D7D7318C FOREIGN KEY (lifecycle_id) REFERENCES sdk_lifecycle (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO sdk_task (id, short_description, help, version, successor, deprecated) SELECT id, short_description, help, version, successor, deprecated FROM __temp__sdk_task');
        $this->addSql('DROP TABLE __temp__sdk_task');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_934E8256D7D7318C ON sdk_task (lifecycle_id)');
    }

    /**
     * @return void
     */
    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sdk_file');
        $this->addSql('DROP TABLE sdk_lifecycle');
        $this->addSql('DROP TABLE sdk_removed_event');
        $this->addSql('DROP INDEX IDX_95DDEAAD8DB60186');
        $this->addSql('DROP INDEX IDX_95DDEAAD5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_command AS SELECT id, task_id, command, type, has_stop_on_error FROM sdk_command');
        $this->addSql('DROP TABLE sdk_command');
        $this->addSql('CREATE TABLE sdk_command (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id VARCHAR(255) DEFAULT NULL, command VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_stop_on_error BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO sdk_command (id, task_id, command, type, has_stop_on_error) SELECT id, task_id, command, type, has_stop_on_error FROM __temp__sdk_command');
        $this->addSql('DROP TABLE __temp__sdk_command');
        $this->addSql('CREATE INDEX IDX_95DDEAAD8DB60186 ON sdk_command (task_id)');
        $this->addSql('DROP INDEX IDX_6C373D6E8DB60186');
        $this->addSql('DROP INDEX IDX_6C373D6E5CA6D57F');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_placeholder AS SELECT id, task_id, name, value_resolver, configuration, is_optional FROM sdk_placeholder');
        $this->addSql('DROP TABLE sdk_placeholder');
        $this->addSql('CREATE TABLE sdk_placeholder (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, value_resolver VARCHAR(255) NOT NULL, configuration CLOB NOT NULL --(DC2Type:json)
        , is_optional BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO sdk_placeholder (id, task_id, name, value_resolver, configuration, is_optional) SELECT id, task_id, name, value_resolver, configuration, is_optional FROM __temp__sdk_placeholder');
        $this->addSql('DROP TABLE __temp__sdk_placeholder');
        $this->addSql('CREATE INDEX IDX_6C373D6E8DB60186 ON sdk_placeholder (task_id)');
        $this->addSql('DROP INDEX UNIQ_934E8256D7D7318C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_task AS SELECT id, short_description, help, version, successor, deprecated FROM sdk_task');
        $this->addSql('DROP TABLE sdk_task');
        $this->addSql('CREATE TABLE sdk_task (id VARCHAR(255) NOT NULL, short_description VARCHAR(255) NOT NULL, help VARCHAR(255) DEFAULT NULL, version VARCHAR(255) NOT NULL, successor VARCHAR(255) DEFAULT NULL, deprecated BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO sdk_task (id, short_description, help, version, successor, deprecated) SELECT id, short_description, help, version, successor, deprecated FROM __temp__sdk_task');
        $this->addSql('DROP TABLE __temp__sdk_task');
    }
}
