<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220418145204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B523653981');
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B5A7BB6B9C');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B523653981 FOREIGN KEY (fk_product) REFERENCES product (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B5A7BB6B9C FOREIGN KEY (fk_color) REFERENCES color (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B523653981');
        $this->addSql('ALTER TABLE product_color DROP FOREIGN KEY FK_C70A33B5A7BB6B9C');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B523653981 FOREIGN KEY (fk_product) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE product_color ADD CONSTRAINT FK_C70A33B5A7BB6B9C FOREIGN KEY (fk_color) REFERENCES color (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
