<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250116131016 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE donnees_virus (id INT AUTO_INCREMENT NOT NULL, date_du_jour DATE NOT NULL, total_cas INT NOT NULL, total_mort INT NOT NULL, nouveau_cas INT NOT NULL, nouveau_mort INT NOT NULL, ID_Pays INT NOT NULL, ID_Virus INT NOT NULL, INDEX IDX_45E2A83D79CB338 (ID_Pays), INDEX IDX_45E2A8323207E0E (ID_Virus), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pays (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, code_lettre VARCHAR(10) NOT NULL, code_chiffre VARCHAR(10) NOT NULL, UNIQUE INDEX UNIQ_349F3CAE77D4B7F9 (code_lettre), UNIQUE INDEX UNIQ_349F3CAE1DD6C47F (code_chiffre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE virus (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, date_apparition DATE NOT NULL, date_fin DATE NOT NULL, description LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE donnees_virus ADD CONSTRAINT FK_45E2A83D79CB338 FOREIGN KEY (ID_Pays) REFERENCES pays (id)');
        $this->addSql('ALTER TABLE donnees_virus ADD CONSTRAINT FK_45E2A8323207E0E FOREIGN KEY (ID_Virus) REFERENCES virus (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE donnees_virus DROP FOREIGN KEY FK_45E2A83D79CB338');
        $this->addSql('ALTER TABLE donnees_virus DROP FOREIGN KEY FK_45E2A8323207E0E');
        $this->addSql('DROP TABLE donnees_virus');
        $this->addSql('DROP TABLE pays');
        $this->addSql('DROP TABLE virus');
    }
}
