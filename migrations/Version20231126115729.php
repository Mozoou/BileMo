<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126115729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455979B1AD6');
        $this->addSql('ALTER TABLE client CHANGE clientname username VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455979B1AD6 FOREIGN KEY (company_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455979B1AD6');
        $this->addSql('ALTER TABLE client CHANGE username clientname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455979B1AD6 FOREIGN KEY (company_id) REFERENCES client (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
