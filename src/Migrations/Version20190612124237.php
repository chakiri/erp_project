<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190612124237 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders_has_products ADD id INT AUTO_INCREMENT NOT NULL, ADD quantity INT NOT NULL, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE orders_has_products ADD CONSTRAINT FK_B27563E04584665A FOREIGN KEY (product_id) REFERENCES product (id)');
        $this->addSql('ALTER TABLE orders_has_products ADD CONSTRAINT FK_B27563E08D9F6D38 FOREIGN KEY (order_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders_has_products MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE orders_has_products DROP FOREIGN KEY FK_B27563E04584665A');
        $this->addSql('ALTER TABLE orders_has_products DROP FOREIGN KEY FK_B27563E08D9F6D38');
        $this->addSql('ALTER TABLE orders_has_products DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE orders_has_products DROP id, DROP quantity');
    }
}
