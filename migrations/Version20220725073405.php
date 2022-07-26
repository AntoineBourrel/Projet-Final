<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220725073405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD published_at DATE NOT NULL');
        $this->addSql('ALTER TABLE demande ADD published_at DATE NOT NULL');
        $this->addSql('ALTER TABLE offre ADD published_at DATE NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP published_at');
        $this->addSql('ALTER TABLE demande DROP published_at');
        $this->addSql('ALTER TABLE offre DROP published_at');
    }
}
