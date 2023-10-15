<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231015115558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_F33F81642B36786B2D5B0234 ON joboffer');
        $this->addSql('CREATE FULLTEXT INDEX IDX_F33F81642B36786B2D5B02346DE44026 ON joboffer (title, city, description)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_F33F81642B36786B2D5B02346DE44026 ON joboffer');
        $this->addSql('CREATE FULLTEXT INDEX IDX_F33F81642B36786B2D5B0234 ON joboffer (title, city)');
    }
}
