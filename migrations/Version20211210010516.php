<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211210010516 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE club (id INT AUTO_INCREMENT NOT NULL, nom_club VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, club_id INT DEFAULT NULL, tournoi_id INT DEFAULT NULL, nom_equipe VARCHAR(255) NOT NULL, INDEX IDX_2449BA1561190A32 (club_id), INDEX IDX_2449BA15F607770A (tournoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poule (id INT AUTO_INCREMENT NOT NULL, nom_poule VARCHAR(255) NOT NULL, point INT NOT NULL, jeux INT NOT NULL, gagne INT NOT NULL, nul INT NOT NULL, perdu INT NOT NULL, set_gagne INT NOT NULL, set_perdu INT NOT NULL, diff INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE poule_equipe (poule_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_EE99353E26596FD8 (poule_id), INDEX IDX_EE99353E6D861B89 (equipe_id), PRIMARY KEY(poule_id, equipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour (id INT AUTO_INCREMENT NOT NULL, tournoi_id INT DEFAULT NULL, nom_tour VARCHAR(255) NOT NULL, INDEX IDX_6AD1F969F607770A (tournoi_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tour_equipe (tour_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_11C75DFF15ED8D43 (tour_id), INDEX IDX_11C75DFF6D861B89 (equipe_id), PRIMARY KEY(tour_id, equipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tournoi (id INT AUTO_INCREMENT NOT NULL, ev_id INT DEFAULT NULL, nom_tournoi VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_18AFD9DF40A4EC42 (ev_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA1561190A32 FOREIGN KEY (club_id) REFERENCES club (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE poule_equipe ADD CONSTRAINT FK_EE99353E26596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE poule_equipe ADD CONSTRAINT FK_EE99353E6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tour ADD CONSTRAINT FK_6AD1F969F607770A FOREIGN KEY (tournoi_id) REFERENCES tournoi (id)');
        $this->addSql('ALTER TABLE tour_equipe ADD CONSTRAINT FK_11C75DFF15ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tour_equipe ADD CONSTRAINT FK_11C75DFF6D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tournoi ADD CONSTRAINT FK_18AFD9DF40A4EC42 FOREIGN KEY (ev_id) REFERENCES evenement (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA1561190A32');
        $this->addSql('ALTER TABLE poule_equipe DROP FOREIGN KEY FK_EE99353E6D861B89');
        $this->addSql('ALTER TABLE tour_equipe DROP FOREIGN KEY FK_11C75DFF6D861B89');
        $this->addSql('ALTER TABLE poule_equipe DROP FOREIGN KEY FK_EE99353E26596FD8');
        $this->addSql('ALTER TABLE tour_equipe DROP FOREIGN KEY FK_11C75DFF15ED8D43');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15F607770A');
        $this->addSql('ALTER TABLE tour DROP FOREIGN KEY FK_6AD1F969F607770A');
        $this->addSql('DROP TABLE club');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE poule');
        $this->addSql('DROP TABLE poule_equipe');
        $this->addSql('DROP TABLE tour');
        $this->addSql('DROP TABLE tour_equipe');
        $this->addSql('DROP TABLE tournoi');
    }
}
