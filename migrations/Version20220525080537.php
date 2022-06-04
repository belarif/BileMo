<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220525080537 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
	    // this up() migration is auto-generated, please modify it to your needs
	    $this->addSql('CREATE TABLE IF NOT EXISTS brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_1C52F9585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_665648E95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_5373C9665E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS customer (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(30) NOT NULL, enabled TINYINT(1) NOT NULL, company VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_81398E094FBF094F (company), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, src VARCHAR(255) NOT NULL, INDEX IDX_C53D045F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS memory (id INT AUTO_INCREMENT NOT NULL, memory_capacity VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_EA6D3435A3B739CF (memory_capacity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS product (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, memory_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(60) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D34A04AD5E237E06 (name), INDEX IDX_D34A04ADF92F3E70 (country_id), INDEX IDX_D34A04ADCCC80CB3 (memory_id), INDEX IDX_D34A04AD44F5D008 (brand_id), INDEX IDX_D34A04ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS product_color (product_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_C70A33B54584665A (product_id), INDEX IDX_C70A33B57ADA1FB5 (color_id), PRIMARY KEY(product_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS role (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_57698A6AE09C0C92 (role_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS user (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6499395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
	    $this->addSql('CREATE TABLE IF NOT EXISTS user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void {
	    // this down() migration is auto-generated, please modify it to your needs
	    $this->addSql('DROP TABLE IF EXISTS brand');
	    $this->addSql('DROP TABLE IF EXISTS color');
	    $this->addSql('DROP TABLE IF EXISTS country');
	    $this->addSql('DROP TABLE IF EXISTS customer');
	    $this->addSql('DROP TABLE IF EXISTS image');
	    $this->addSql('DROP TABLE IF EXISTS memory');
	    $this->addSql('DROP TABLE IF EXISTS product');
	    $this->addSql('DROP TABLE IF EXISTS product_color');
	    $this->addSql('DROP TABLE IF EXISTS role');
	    $this->addSql('DROP TABLE IF EXISTS user');
	    $this->addSql('DROP TABLE IF EXISTS user_role');
    }
}
