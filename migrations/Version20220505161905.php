<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505161905 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE company company VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE memory CHANGE memory_capacity memory_capacity VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE name name VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE customer CHANGE company company VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE memory CHANGE memory_capacity memory_capacity VARCHAR(40) NOT NULL');
        $this->addSql('ALTER TABLE product CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(80) NOT NULL');
    }
}
