<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231004080312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, applicant_id INT NOT NULL, employer_id INT NOT NULL, message VARCHAR(255) NOT NULL, state TINYINT(1) NOT NULL, INDEX IDX_17FD46C197139001 (applicant_id), INDEX IDX_17FD46C141CD9E7A (employer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C197139001 FOREIGN KEY (applicant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C141CD9E7A FOREIGN KEY (employer_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753A41CD9E7A');
        $this->addSql('ALTER TABLE alerte DROP FOREIGN KEY FK_3AE753A97139001');
        $this->addSql('DROP TABLE alerte');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alerte (id INT AUTO_INCREMENT NOT NULL, applicant_id INT NOT NULL, employer_id INT NOT NULL, message VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, state TINYINT(1) NOT NULL, INDEX IDX_3AE753A41CD9E7A (employer_id), INDEX IDX_3AE753A97139001 (applicant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753A41CD9E7A FOREIGN KEY (employer_id) REFERENCES company (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE alerte ADD CONSTRAINT FK_3AE753A97139001 FOREIGN KEY (applicant_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C197139001');
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C141CD9E7A');
        $this->addSql('DROP TABLE alert');
    }
}
