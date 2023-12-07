<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231126112000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD last_name VARCHAR(255) NOT NULL, ADD username VARCHAR(255) NOT NULL, CHANGE name first_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user DROP firstname, DROP lastname, DROP username');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD name VARCHAR(255) NOT NULL, DROP first_name, DROP last_name, DROP username');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, ADD username VARCHAR(255) NOT NULL');
    }
}
