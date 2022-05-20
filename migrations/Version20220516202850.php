<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220516202850 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_81398E094FBF094F ON customer (company)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EA6D3435A3B739CF ON memory (memory_capacity)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D34A04AD5E237E06 ON product (name)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_57698A6AE09C0C92 ON role (role_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_81398E094FBF094F ON customer');
        $this->addSql('DROP INDEX UNIQ_EA6D3435A3B739CF ON memory');
        $this->addSql('DROP INDEX UNIQ_D34A04AD5E237E06 ON product');
        $this->addSql('DROP INDEX UNIQ_57698A6AE09C0C92 ON role');
    }
}
