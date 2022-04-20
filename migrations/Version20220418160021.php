<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418160021 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE product_color');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE product_color (fk_product INT NOT NULL, fk_color INT NOT NULL, INDEX IDX_C70A33B523653981 (fk_product), INDEX IDX_C70A33B5A7BB6B9C (fk_color), PRIMARY KEY(fk_product, fk_color)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B523653981 FOREIGN KEY (fk_product) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B5A7BB6B9C FOREIGN KEY (fk_color) REFERENCES color (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
