<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505105527 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE country CHANGE name name VARCHAR(80) NOT NULL');
        $this->addSql('ALTER TABLE customer CHANGE code code VARCHAR(10) NOT NULL, CHANGE company company VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE memory CHANGE memory_capacity memory_capacity VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand CHANGE name name VARCHAR(150) NOT NULL');
        $this->addSql('ALTER TABLE country CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE customer CHANGE code code VARCHAR(100) NOT NULL, CHANGE company company VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE memory CHANGE memory_capacity memory_capacity VARCHAR(80) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(180) NOT NULL');
    }
}
