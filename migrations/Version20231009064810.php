<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231009064810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resume_technology (resume_id INT NOT NULL, technology_id INT NOT NULL, INDEX IDX_46132084D262AF09 (resume_id), INDEX IDX_461320844235D463 (technology_id), PRIMARY KEY(resume_id, technology_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resume_technology ADD CONSTRAINT FK_46132084D262AF09 FOREIGN KEY (resume_id) REFERENCES resume (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resume_technology ADD CONSTRAINT FK_461320844235D463 FOREIGN KEY (technology_id) REFERENCES technology (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE resume_technology DROP FOREIGN KEY FK_46132084D262AF09');
        $this->addSql('ALTER TABLE resume_technology DROP FOREIGN KEY FK_461320844235D463');
        $this->addSql('DROP TABLE resume_technology');
    }
}
