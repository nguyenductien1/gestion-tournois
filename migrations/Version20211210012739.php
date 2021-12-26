<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211210012739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE jeu (id INT AUTO_INCREMENT NOT NULL, poule_id INT DEFAULT NULL, nom_jeu VARCHAR(255) NOT NULL, point_eq_a INT NOT NULL, point_eq_b INT NOT NULL, INDEX IDX_82E48DB526596FD8 (poule_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_a_jeu (jeu_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_D6B774398C9E392E (jeu_id), INDEX IDX_D6B774396D861B89 (equipe_id), PRIMARY KEY(jeu_id, equipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_b_jeu (jeu_id INT NOT NULL, equipe_id INT NOT NULL, INDEX IDX_91170EE98C9E392E (jeu_id), INDEX IDX_91170EE96D861B89 (equipe_id), PRIMARY KEY(jeu_id, equipe_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE jeu ADD CONSTRAINT FK_82E48DB526596FD8 FOREIGN KEY (poule_id) REFERENCES poule (id)');
        $this->addSql('ALTER TABLE equipe_a_jeu ADD CONSTRAINT FK_D6B774398C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_a_jeu ADD CONSTRAINT FK_D6B774396D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_b_jeu ADD CONSTRAINT FK_91170EE98C9E392E FOREIGN KEY (jeu_id) REFERENCES jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_b_jeu ADD CONSTRAINT FK_91170EE96D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe_a_jeu DROP FOREIGN KEY FK_D6B774398C9E392E');
        $this->addSql('ALTER TABLE equipe_b_jeu DROP FOREIGN KEY FK_91170EE98C9E392E');
        $this->addSql('DROP TABLE jeu');
        $this->addSql('DROP TABLE equipe_a_jeu');
        $this->addSql('DROP TABLE equipe_b_jeu');
    }
}
