<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211210104711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE niveau_jouer (id INT AUTO_INCREMENT NOT NULL, niveau VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jouer ADD niveau_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE jouer ADD CONSTRAINT FK_825E5AEDB3E9C81 FOREIGN KEY (niveau_id) REFERENCES niveau_jouer (id)');
        $this->addSql('CREATE INDEX IDX_825E5AEDB3E9C81 ON jouer (niveau_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE jouer DROP FOREIGN KEY FK_825E5AEDB3E9C81');
        $this->addSql('DROP TABLE niveau_jouer');
        $this->addSql('DROP INDEX IDX_825E5AEDB3E9C81 ON jouer');
        $this->addSql('ALTER TABLE jouer DROP niveau_id');
    }
}
