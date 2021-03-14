<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312120021 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE league (id INT AUTO_INCREMENT NOT NULL, sport_id INT DEFAULT NULL, country VARCHAR(255) DEFAULT NULL, INDEX IDX_3EB4C318AC78BCF8 (sport_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE match_day (id INT AUTO_INCREMENT NOT NULL, number INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE league ADD CONSTRAINT FK_3EB4C318AC78BCF8 FOREIGN KEY (sport_id) REFERENCES sport (id)');
        $this->addSql('ALTER TABLE matcha ADD match_day_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE matcha ADD CONSTRAINT FK_98A71109A8ADB827 FOREIGN KEY (match_day_id) REFERENCES match_day (id)');
        $this->addSql('CREATE INDEX IDX_98A71109A8ADB827 ON matcha (match_day_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matcha DROP FOREIGN KEY FK_98A71109A8ADB827');
        $this->addSql('DROP TABLE league');
        $this->addSql('DROP TABLE match_day');
        $this->addSql('DROP INDEX IDX_98A71109A8ADB827 ON matcha');
        $this->addSql('ALTER TABLE matcha DROP match_day_id');
    }
}
