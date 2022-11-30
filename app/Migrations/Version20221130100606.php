<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221130100606 extends AbstractMigration
{
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return 'Created `sdk_task_set_task_relation` table.';
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sdk_task_set_task_relation (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, task_set_id VARCHAR(255) DEFAULT NULL, sub_task_id VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_407867162B5289CB FOREIGN KEY (task_set_id) REFERENCES sdk_task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_40786716F26E5D72 FOREIGN KEY (sub_task_id) REFERENCES sdk_task (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_407867162B5289CB ON sdk_task_set_task_relation (task_set_id)');
        $this->addSql('CREATE INDEX IDX_40786716F26E5D72 ON sdk_task_set_task_relation (sub_task_id)');
        $this->addSql('CREATE UNIQUE INDEX task_set_sub_task ON sdk_task_set_task_relation (task_set_id, sub_task_id)');
    }

    /**
     * @param \Doctrine\DBAL\Schema\Schema $schema
     *
     * @return void
     */
    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sdk_task_set_task_relation');
    }
}
