<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250802001421 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ticket (id SERIAL NOT NULL, email VARCHAR(64) NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, close_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, description VARCHAR(512) NOT NULL, category VARCHAR(32) NOT NULL, status VARCHAR(32) NOT NULL, owner VARCHAR(64) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE "user" ALTER is_verified SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('ALTER TABLE "user" ALTER is_verified DROP NOT NULL');
    }
}
