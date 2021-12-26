<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211209235213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom_evt VARCHAR(255) NOT NULL, date_debut DATETIME NOT NULL, date_termn DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_jeu (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_80F36B54FD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE type_jeu ADD CONSTRAINT FK_80F36B54FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_jeu DROP FOREIGN KEY FK_80F36B54FD02F13');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE type_jeu');
    }
}
