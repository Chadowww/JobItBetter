<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231008114912 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE alert (id INT AUTO_INCREMENT NOT NULL, applicant_id INT NOT NULL, employer_id INT NOT NULL, message VARCHAR(255) NOT NULL, state TINYINT(1) NOT NULL, INDEX IDX_17FD46C197139001 (applicant_id), INDEX IDX_17FD46C141CD9E7A (employer_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE company (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, logo VARCHAR(255) DEFAULT NULL, siret VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4FBF094F26E94372 (siret), UNIQUE INDEX UNIQ_4FBF094FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_FBD8E0F812469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joboffer (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, job_id INT DEFAULT NULL, contract_id INT DEFAULT NULL, salary_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, city VARCHAR(50) NOT NULL, salary_min INT DEFAULT NULL, salary_max INT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F33F8164979B1AD6 (company_id), INDEX IDX_F33F8164BE04EA9 (job_id), INDEX IDX_F33F81642576E0FD (contract_id), INDEX IDX_F33F8164B0FDF16E (salary_id), FULLTEXT INDEX IDX_F33F81642B36786B2D5B0234 (title, city), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joboffer_user (joboffer_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_F947B652BD612208 (joboffer_id), INDEX IDX_F947B652A76ED395 (user_id), PRIMARY KEY(joboffer_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resume (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(100) DEFAULT NULL, path VARCHAR(255) NOT NULL, INDEX IDX_60C1D0A0A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE salary (id INT AUTO_INCREMENT NOT NULL, min INT NOT NULL, max INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE search (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, company_id INT DEFAULT NULL, job_id INT DEFAULT NULL, contract_id INT DEFAULT NULL, salary_id INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, INDEX IDX_B4F0DBA7A76ED395 (user_id), INDEX IDX_B4F0DBA7979B1AD6 (company_id), INDEX IDX_B4F0DBA7BE04EA9 (job_id), INDEX IDX_B4F0DBA72576E0FD (contract_id), INDEX IDX_B4F0DBA7B0FDF16E (salary_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(50) NOT NULL, lastname VARCHAR(100) NOT NULL, birthday DATE DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, phone VARCHAR(15) DEFAULT NULL, status INT DEFAULT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE favlist (user_id INT NOT NULL, joboffer_id INT NOT NULL, INDEX IDX_A5BC4835A76ED395 (user_id), INDEX IDX_A5BC4835BD612208 (joboffer_id), PRIMARY KEY(user_id, joboffer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C197139001 FOREIGN KEY (applicant_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE alert ADD CONSTRAINT FK_17FD46C141CD9E7A FOREIGN KEY (employer_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE company ADD CONSTRAINT FK_4FBF094FA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F812469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE joboffer ADD CONSTRAINT FK_F33F8164979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE joboffer ADD CONSTRAINT FK_F33F8164BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE joboffer ADD CONSTRAINT FK_F33F81642576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE joboffer ADD CONSTRAINT FK_F33F8164B0FDF16E FOREIGN KEY (salary_id) REFERENCES salary (id)');
        $this->addSql('ALTER TABLE joboffer_user ADD CONSTRAINT FK_F947B652BD612208 FOREIGN KEY (joboffer_id) REFERENCES joboffer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE joboffer_user ADD CONSTRAINT FK_F947B652A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE resume ADD CONSTRAINT FK_60C1D0A0A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA7A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA7979B1AD6 FOREIGN KEY (company_id) REFERENCES company (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA7BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA72576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id)');
        $this->addSql('ALTER TABLE search ADD CONSTRAINT FK_B4F0DBA7B0FDF16E FOREIGN KEY (salary_id) REFERENCES salary (id)');
        $this->addSql('ALTER TABLE favlist ADD CONSTRAINT FK_A5BC4835A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE favlist ADD CONSTRAINT FK_A5BC4835BD612208 FOREIGN KEY (joboffer_id) REFERENCES joboffer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C197139001');
        $this->addSql('ALTER TABLE alert DROP FOREIGN KEY FK_17FD46C141CD9E7A');
        $this->addSql('ALTER TABLE company DROP FOREIGN KEY FK_4FBF094FA76ED395');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F812469DE2');
        $this->addSql('ALTER TABLE joboffer DROP FOREIGN KEY FK_F33F8164979B1AD6');
        $this->addSql('ALTER TABLE joboffer DROP FOREIGN KEY FK_F33F8164BE04EA9');
        $this->addSql('ALTER TABLE joboffer DROP FOREIGN KEY FK_F33F81642576E0FD');
        $this->addSql('ALTER TABLE joboffer DROP FOREIGN KEY FK_F33F8164B0FDF16E');
        $this->addSql('ALTER TABLE joboffer_user DROP FOREIGN KEY FK_F947B652BD612208');
        $this->addSql('ALTER TABLE joboffer_user DROP FOREIGN KEY FK_F947B652A76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE resume DROP FOREIGN KEY FK_60C1D0A0A76ED395');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA7A76ED395');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA7979B1AD6');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA7BE04EA9');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA72576E0FD');
        $this->addSql('ALTER TABLE search DROP FOREIGN KEY FK_B4F0DBA7B0FDF16E');
        $this->addSql('ALTER TABLE favlist DROP FOREIGN KEY FK_A5BC4835A76ED395');
        $this->addSql('ALTER TABLE favlist DROP FOREIGN KEY FK_A5BC4835BD612208');
        $this->addSql('DROP TABLE alert');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE company');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE joboffer');
        $this->addSql('DROP TABLE joboffer_user');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE resume');
        $this->addSql('DROP TABLE salary');
        $this->addSql('DROP TABLE search');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE favlist');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
