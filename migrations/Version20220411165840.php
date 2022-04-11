<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220411165840 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE memory (id INT AUTO_INCREMENT NOT NULL, memory_capacity VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE product ADD memory_id INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCCC80CB3 FOREIGN KEY (memory_id) REFERENCES memory (id)');
        $this->addSql('CREATE INDEX IDX_D34A04ADCCC80CB3 ON product (memory_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCCC80CB3');
        $this->addSql('DROP TABLE memory');
        $this->addSql('DROP INDEX IDX_D34A04ADCCC80CB3 ON product');
        $this->addSql('ALTER TABLE product DROP memory_id');
    }
}
