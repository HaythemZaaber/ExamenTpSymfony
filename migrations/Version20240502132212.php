<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240502132212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE analyse (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, date INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, birthday INT NOT NULL, adresse VARCHAR(255) NOT NULL, phone INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE patient_analyse (patient_id INT NOT NULL, analyse_id INT NOT NULL, INDEX IDX_687859956B899279 (patient_id), INDEX IDX_687859951EFE06BF (analyse_id), PRIMARY KEY(patient_id, analyse_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE patient_analyse ADD CONSTRAINT FK_687859956B899279 FOREIGN KEY (patient_id) REFERENCES patient (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE patient_analyse ADD CONSTRAINT FK_687859951EFE06BF FOREIGN KEY (analyse_id) REFERENCES analyse (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE patient_analyse DROP FOREIGN KEY FK_687859956B899279');
        $this->addSql('ALTER TABLE patient_analyse DROP FOREIGN KEY FK_687859951EFE06BF');
        $this->addSql('DROP TABLE analyse');
        $this->addSql('DROP TABLE patient');
        $this->addSql('DROP TABLE patient_analyse');
    }
}
