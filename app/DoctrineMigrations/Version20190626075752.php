<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20190626075752 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE video (id INT AUTO_INCREMENT NOT NULL, serie_id INT DEFAULT NULL, product_category_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, position SMALLINT NOT NULL, status SMALLINT NOT NULL, updated_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, video VARCHAR(255) DEFAULT NULL, video_id VARCHAR(255) DEFAULT NULL, video_width SMALLINT DEFAULT NULL, video_height SMALLINT DEFAULT NULL, video_duration SMALLINT DEFAULT NULL, thumbnail_url VARCHAR(255) DEFAULT NULL, thumbnail_width SMALLINT DEFAULT NULL, thumbnail_height SMALLINT DEFAULT NULL, thumbnail_url_play_button VARCHAR(255) DEFAULT NULL, video_embed TEXT DEFAULT NULL, public_date DATE DEFAULT NULL, INDEX IDX_7CC7DA2CD94388BD (serie_id), INDEX IDX_7CC7DA2CBE6903FD (product_category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_translation (id INT AUTO_INCREMENT NOT NULL, translatable_id INT DEFAULT NULL, title VARCHAR(255) DEFAULT NULL, description TEXT DEFAULT NULL, locale VARCHAR(255) NOT NULL, INDEX IDX_1FE096EF2C2AC5D3 (translatable_id), INDEX search_idx (title), UNIQUE INDEX event_translation_unique_translation (translatable_id, locale), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CD94388BD FOREIGN KEY (serie_id) REFERENCES series (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id)');
        $this->addSql('ALTER TABLE event_translation ADD CONSTRAINT FK_1FE096EF2C2AC5D3 FOREIGN KEY (translatable_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product CHANGE is_new is_new TINYINT(1) DEFAULT \'0\' NOT NULL');
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

        $this->addSql('ALTER TABLE event_translation DROP FOREIGN KEY FK_1FE096EF2C2AC5D3');
        $this->addSql('DROP TABLE video');
        $this->addSql('DROP TABLE event_translation');
        $this->addSql('ALTER TABLE product CHANGE is_new is_new TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE shipping_rate CHANGE free_shipping free_shipping TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE sku CHANGE default_option default_option TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
