<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230729194701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE image image VARCHAR(255) DEFAULT NULL, CHANGE secteur secteur VARCHAR(255) DEFAULT NULL, CHANGE contrat contrat VARCHAR(255) DEFAULT NULL, CHANGE sortie sortie DATE DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE image image VARCHAR(255) NOT NULL, CHANGE secteur secteur VARCHAR(255) NOT NULL, CHANGE contrat contrat VARCHAR(255) NOT NULL, CHANGE sortie sortie DATE NOT NULL');
    }
}
