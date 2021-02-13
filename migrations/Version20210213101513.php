<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210213101513 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE betroom (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, status INT NOT NULL, public TINYINT(1) NOT NULL, date DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE betroom_user (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, betroom_id INT NOT NULL, status INT NOT NULL, date DATETIME NOT NULL, INDEX IDX_690303E3A76ED395 (user_id), INDEX IDX_690303E3C10D4857 (betroom_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE betroom_user ADD CONSTRAINT FK_690303E3A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE betroom_user ADD CONSTRAINT FK_690303E3C10D4857 FOREIGN KEY (betroom_id) REFERENCES betroom (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE betroom_user DROP FOREIGN KEY FK_690303E3C10D4857');
        $this->addSql('DROP TABLE betroom');
        $this->addSql('DROP TABLE betroom_user');
    }
}
