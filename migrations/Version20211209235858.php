<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211209235858 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement_type_jeu (evenement_id INT NOT NULL, type_jeu_id INT NOT NULL, INDEX IDX_F95F0BCFD02F13 (evenement_id), INDEX IDX_F95F0BCAF495216 (type_jeu_id), PRIMARY KEY(evenement_id, type_jeu_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evenement_type_jeu ADD CONSTRAINT FK_F95F0BCFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement_type_jeu ADD CONSTRAINT FK_F95F0BCAF495216 FOREIGN KEY (type_jeu_id) REFERENCES type_jeu (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement CHANGE nom_evt nom_ev VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE type_jeu DROP FOREIGN KEY FK_80F36B54FD02F13');
        $this->addSql('DROP INDEX IDX_80F36B54FD02F13 ON type_jeu');
        $this->addSql('ALTER TABLE type_jeu DROP evenement_id, CHANGE type type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE evenement_type_jeu');
        $this->addSql('ALTER TABLE evenement CHANGE nom_ev nom_evt VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type_jeu ADD evenement_id INT DEFAULT NULL, CHANGE type type VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type_jeu ADD CONSTRAINT FK_80F36B54FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_80F36B54FD02F13 ON type_jeu (evenement_id)');
    }
}
