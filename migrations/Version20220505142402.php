<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220505142402 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand CHANGE name name VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE country CHANGE name name VARCHAR(60) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(80) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE brand CHANGE name name VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE country CHANGE name name VARCHAR(80) NOT NULL');
        $this->addSql('ALTER TABLE user CHANGE email email VARCHAR(100) NOT NULL');
    }
}
