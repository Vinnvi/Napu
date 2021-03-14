<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312122755 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE season (id INT AUTO_INCREMENT NOT NULL, league_id INT NOT NULL, INDEX IDX_F0E45BA958AFC4DE (league_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE season ADD CONSTRAINT FK_F0E45BA958AFC4DE FOREIGN KEY (league_id) REFERENCES league (id)');
        $this->addSql('ALTER TABLE match_day ADD season_id INT NOT NULL');
        $this->addSql('ALTER TABLE match_day ADD CONSTRAINT FK_E1EE884E4EC001D1 FOREIGN KEY (season_id) REFERENCES season (id)');
        $this->addSql('CREATE INDEX IDX_E1EE884E4EC001D1 ON match_day (season_id)');
        $this->addSql('ALTER TABLE matcha ADD status VARCHAR(255) NOT NULL, ADD infos JSON DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE match_day DROP FOREIGN KEY FK_E1EE884E4EC001D1');
        $this->addSql('DROP TABLE season');
        $this->addSql('DROP INDEX IDX_E1EE884E4EC001D1 ON match_day');
        $this->addSql('ALTER TABLE match_day DROP season_id');
        $this->addSql('ALTER TABLE matcha DROP status, DROP infos');
    }
}
