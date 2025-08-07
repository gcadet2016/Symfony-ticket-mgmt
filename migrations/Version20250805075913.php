<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805075913 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE category_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE status_id_seq CASCADE');
        $this->addSql('ALTER TABLE ticket ADD category_fk INT NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD status_fk INT NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP category');
        $this->addSql('ALTER TABLE ticket DROP status');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA35619CBC FOREIGN KEY (category_fk) REFERENCES category (category_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA37CD001E3 FOREIGN KEY (status_fk) REFERENCES status (status_id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_97A0ADA35619CBC ON ticket (category_fk)');
        $this->addSql('CREATE INDEX IDX_97A0ADA37CD001E3 ON ticket (status_fk)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE category_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE status_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA35619CBC');
        $this->addSql('ALTER TABLE ticket DROP CONSTRAINT FK_97A0ADA37CD001E3');
        $this->addSql('DROP INDEX IDX_97A0ADA35619CBC');
        $this->addSql('DROP INDEX IDX_97A0ADA37CD001E3');
        $this->addSql('ALTER TABLE ticket ADD category VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE ticket ADD status VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE ticket DROP category_fk');
        $this->addSql('ALTER TABLE ticket DROP status_fk');
    }
}
