<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210217153728 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE betroom_rules (id INT AUTO_INCREMENT NOT NULL, betroom_id INT NOT NULL, rules JSON DEFAULT NULL, UNIQUE INDEX UNIQ_BF7D431FC10D4857 (betroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE betroom_rules ADD CONSTRAINT FK_BF7D431FC10D4857 FOREIGN KEY (betroom_id) REFERENCES betroom (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE betroom_rules');
    }
}
