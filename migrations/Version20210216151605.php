<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210216151605 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bet_rules (id INT AUTO_INCREMENT NOT NULL, betroom_id INT NOT NULL, matcha_id INT NOT NULL, rules JSON DEFAULT NULL, INDEX IDX_A6D03368C10D4857 (betroom_id), INDEX IDX_A6D0336872FBEB7C (matcha_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE betroom_match (id INT AUTO_INCREMENT NOT NULL, betroom_id INT NOT NULL, matcha_id INT NOT NULL, INDEX IDX_4CBC1F26C10D4857 (betroom_id), INDEX IDX_4CBC1F2672FBEB7C (matcha_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bet_rules ADD CONSTRAINT FK_A6D03368C10D4857 FOREIGN KEY (betroom_id) REFERENCES betroom (id)');
        $this->addSql('ALTER TABLE bet_rules ADD CONSTRAINT FK_A6D0336872FBEB7C FOREIGN KEY (matcha_id) REFERENCES matcha (id)');
        $this->addSql('ALTER TABLE betroom_match ADD CONSTRAINT FK_4CBC1F26C10D4857 FOREIGN KEY (betroom_id) REFERENCES betroom (id)');
        $this->addSql('ALTER TABLE betroom_match ADD CONSTRAINT FK_4CBC1F2672FBEB7C FOREIGN KEY (matcha_id) REFERENCES matcha (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bet_rules');
        $this->addSql('DROP TABLE betroom_match');
    }
}
