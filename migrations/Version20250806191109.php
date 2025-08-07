<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250806191109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE category_category_id_seq');
        $this->addSql('SELECT setval(\'category_category_id_seq\', (SELECT MAX(category_id) FROM category))');
        $this->addSql('ALTER TABLE category ALTER category_id SET DEFAULT nextval(\'category_category_id_seq\')');
        $this->addSql('CREATE SEQUENCE status_status_id_seq');
        $this->addSql('SELECT setval(\'status_status_id_seq\', (SELECT MAX(status_id) FROM status))');
        $this->addSql('ALTER TABLE status ALTER status_id SET DEFAULT nextval(\'status_status_id_seq\')');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE status ALTER status_id DROP DEFAULT');
        $this->addSql('ALTER TABLE category ALTER category_id DROP DEFAULT');
    }
}
