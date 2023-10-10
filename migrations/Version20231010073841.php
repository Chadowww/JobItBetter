<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231010073841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE technology ADD CONSTRAINT FK_F463524D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_F463524D12469DE2 ON technology (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE technology DROP FOREIGN KEY FK_F463524D12469DE2');
        $this->addSql('DROP INDEX IDX_F463524D12469DE2 ON technology');
        $this->addSql('ALTER TABLE technology DROP category_id');
    }
}
