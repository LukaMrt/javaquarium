<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251116150955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE algae ADD aquarium_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE algae ADD CONSTRAINT FK_1F26F3CF7051F3DE FOREIGN KEY (aquarium_id) REFERENCES aquarium (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_1F26F3CF7051F3DE ON algae (aquarium_id)');
        $this->addSql('ALTER TABLE fish ADD aquarium_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE fish ALTER species TYPE VARCHAR(9)');
        $this->addSql('ALTER TABLE fish ALTER sex TYPE VARCHAR(6)');
        $this->addSql('ALTER TABLE fish ADD CONSTRAINT FK_3F7444337051F3DE FOREIGN KEY (aquarium_id) REFERENCES aquarium (id) ON DELETE CASCADE NOT DEFERRABLE');
        $this->addSql('CREATE INDEX IDX_3F7444337051F3DE ON fish (aquarium_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE algae DROP CONSTRAINT FK_1F26F3CF7051F3DE');
        $this->addSql('DROP INDEX IDX_1F26F3CF7051F3DE');
        $this->addSql('ALTER TABLE algae DROP aquarium_id');
        $this->addSql('ALTER TABLE fish DROP CONSTRAINT FK_3F7444337051F3DE');
        $this->addSql('DROP INDEX IDX_3F7444337051F3DE');
        $this->addSql('ALTER TABLE fish DROP aquarium_id');
        $this->addSql('ALTER TABLE fish ALTER species TYPE VARCHAR(50)');
        $this->addSql('ALTER TABLE fish ALTER sex TYPE VARCHAR(10)');
    }
}
