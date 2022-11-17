<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20221115141126 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added `force_ask_value` column.';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_setting AS SELECT id, path, "values", strategy, type, has_initialization, initialization_description, initializer, setting_type FROM sdk_setting');
        $this->addSql('DROP TABLE sdk_setting');
        $this->addSql('CREATE TABLE sdk_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, "values" CLOB DEFAULT NULL --(DC2Type:json)
        , strategy VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL, initializer VARCHAR(255) DEFAULT NULL, setting_type CHECK(setting_type IN (\'sdk\',\'local\',\'shared\')), force_ask_value BOOLEAN NOT NULL DEFAULT 0)');
        $this->addSql('INSERT INTO sdk_setting (id, path, "values", strategy, type, has_initialization, initialization_description, initializer, setting_type) SELECT id, path, "values", strategy, type, has_initialization, initialization_description, initializer, setting_type FROM __temp__sdk_setting');
        $this->addSql('DROP TABLE __temp__sdk_setting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE TEMPORARY TABLE __temp__sdk_setting AS SELECT id, path, "values", strategy, type, setting_type, has_initialization, initialization_description, initializer FROM sdk_setting');
        $this->addSql('DROP TABLE sdk_setting');
        $this->addSql('CREATE TABLE sdk_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, path VARCHAR(255) NOT NULL, "values" CLOB DEFAULT NULL --(DC2Type:json)
        , strategy VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, setting_type CHECK(setting_type IN (\'sdk\',\'local\',\'shared\')), has_initialization BOOLEAN NOT NULL, initialization_description VARCHAR(255) DEFAULT NULL, initializer VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO sdk_setting (id, path, "values", strategy, type, setting_type, has_initialization, initialization_description, initializer) SELECT id, path, "values", strategy, type, setting_type, has_initialization, initialization_description, initializer FROM __temp__sdk_setting');
        $this->addSql('DROP TABLE __temp__sdk_setting');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8463B8E1B548B0F ON sdk_setting (path)');
    }
}
