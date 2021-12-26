<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211214175630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poule ADD tour_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE poule ADD CONSTRAINT FK_FA1FEB4015ED8D43 FOREIGN KEY (tour_id) REFERENCES tour (id)');
        $this->addSql('CREATE INDEX IDX_FA1FEB4015ED8D43 ON poule (tour_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE poule DROP FOREIGN KEY FK_FA1FEB4015ED8D43');
        $this->addSql('DROP INDEX IDX_FA1FEB4015ED8D43 ON poule');
        $this->addSql('ALTER TABLE poule DROP tour_id');
    }
}
