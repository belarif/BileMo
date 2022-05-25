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
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_1C52F9585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_665648E95E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_5373C9665E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE customer (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(30) NOT NULL, enabled TINYINT(1) NOT NULL, company VARCHAR(60) NOT NULL, UNIQUE INDEX UNIQ_81398E094FBF094F (company), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, product_id INT NOT NULL, src VARCHAR(255) NOT NULL, INDEX IDX_C53D045F4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE memory (id INT AUTO_INCREMENT NOT NULL, memory_capacity VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_EA6D3435A3B739CF (memory_capacity), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, country_id INT DEFAULT NULL, memory_id INT DEFAULT NULL, brand_id INT DEFAULT NULL, user_id INT NOT NULL, name VARCHAR(60) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_D34A04AD5E237E06 (name), INDEX IDX_D34A04ADF92F3E70 (country_id), INDEX IDX_D34A04ADCCC80CB3 (memory_id), INDEX IDX_D34A04AD44F5D008 (brand_id), INDEX IDX_D34A04ADA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_color (product_id INT NOT NULL, color_id INT NOT NULL, INDEX IDX_C70A33B54584665A (product_id), INDEX IDX_C70A33B57ADA1FB5 (color_id), PRIMARY KEY(product_id, color_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(30) NOT NULL, UNIQUE INDEX UNIQ_57698A6AE09C0C92 (role_name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, customer_id INT DEFAULT NULL, email VARCHAR(50) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6499395C3F3 (customer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_role (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_2DE8C6A3A76ED395 (user_id), INDEX IDX_2DE8C6A3D60322AC (role_id), PRIMARY KEY(user_id, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADCCC80CB3 FOREIGN KEY (memory_id) REFERENCES memory (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04AD44F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B54584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B57ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6499395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id)');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_role ADD CONSTRAINT FK_2DE8C6A3D60322AC FOREIGN KEY (role_id) REFERENCES role (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04AD44F5D008');
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B57ADA1FB5');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADF92F3E70');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6499395C3F3');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADCCC80CB3');
        $this->addSql('ALTER TABLE image DROP FOREIGN KEY FK_C53D045F4584665A');
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B54584665A');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3D60322AC');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADA76ED395');
        $this->addSql('ALTER TABLE user_role DROP FOREIGN KEY FK_2DE8C6A3A76ED395');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE memory');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_color');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_role');
    }
}
