<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221101164022 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added force_ask_value column into  sdk_setting table.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sdk_setting ADD COLUMN force_ask_value BOOLEAN NOT NULL DEFAULT 0');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sdk_setting DROP COLUMN force_ask_value');
    }
}
