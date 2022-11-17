<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220726103404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Adjusted sdk_setting table with setting_type field.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_setting AS SELECT id, path, "values", strategy, type, is_project, has_initialization, initialization_description, initializer FROM sdk_setting');
        $this->addSql('DROP TABLE sdk_setting');
        $this->addSql('CREATE TABLE sdk_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, "values" CLOB DEFAULT NULL --(DC2Type:json)
        , strategy VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL, initializer VARCHAR(255) DEFAULT NULL, setting_type CHECK(setting_type IN (\'sdk\',\'local\',\'shared\')))');
        $this->addSql('INSERT INTO sdk_setting (id, path, "values", strategy, type, setting_type, has_initialization, initialization_description, initializer) SELECT id, path, "values", strategy, type, (CASE WHEN is_project = 1 THEN "local" ELSE "sdk" END) AS setting_type , has_initialization, initialization_description, initializer FROM __temp__sdk_setting');
        $this->addSql('DROP TABLE __temp__sdk_setting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_setting AS SELECT id, path, "values", strategy, type, setting_type, has_initialization, initialization_description, initializer FROM sdk_setting');
        $this->addSql('DROP TABLE sdk_setting');
        $this->addSql('CREATE TABLE sdk_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, "values" CLOB DEFAULT NULL --(DC2Type:json)
        , strategy VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL, initializer VARCHAR(255) DEFAULT NULL, is_project BOOLEAN NOT NULL)');
        $this->addSql('INSERT INTO sdk_setting (id, path, "values", strategy, type, is_project, has_initialization, initialization_description, initializer) SELECT id, path, "values", strategy, type, (CASE WHEN setting_type = "sdk" THEN 1 ELSE 0 END) AS is_project, has_initialization, initialization_description, initializer FROM __temp__sdk_setting');
        $this->addSql('DROP TABLE __temp__sdk_setting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
    }
}
