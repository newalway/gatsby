<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190702034724 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product ADD video VARCHAR(255) DEFAULT NULL, ADD video_id VARCHAR(255) DEFAULT NULL, ADD video_width SMALLINT DEFAULT NULL, ADD video_height SMALLINT DEFAULT NULL, ADD video_duration SMALLINT DEFAULT NULL, ADD thumbnail_url VARCHAR(255) DEFAULT NULL, ADD thumbnail_width SMALLINT DEFAULT NULL, ADD thumbnail_height SMALLINT DEFAULT NULL, ADD thumbnail_url_play_button VARCHAR(255) DEFAULT NULL, ADD video_embed TEXT DEFAULT NULL, CHANGE is_new is_new TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE product_translation ADD net_weight TEXT DEFAULT NULL, CHANGE ingredient special_feature TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE shipping_rate CHANGE free_shipping free_shipping TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE sku CHANGE default_option default_option TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE product DROP video, DROP video_id, DROP video_width, DROP video_height, DROP video_duration, DROP thumbnail_url, DROP thumbnail_width, DROP thumbnail_height, DROP thumbnail_url_play_button, DROP video_embed, CHANGE is_new is_new TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE product_translation ADD ingredient TEXT DEFAULT NULL COLLATE utf8_unicode_ci, DROP special_feature, DROP net_weight');
        $this->addSql('ALTER TABLE shipping_rate CHANGE free_shipping free_shipping TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE sku CHANGE default_option default_option TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
