<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810164312 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket DROP FOREIGN KEY FK_2246507B9D86650F');
        $this->addSql('DROP INDEX IDX_2246507B9D86650F ON basket');
        $this->addSql('ALTER TABLE basket DROP user_id_id');
        $this->addSql('ALTER TABLE user ADD lastname VARCHAR(255) NOT NULL, ADD name VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basket ADD user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE basket ADD CONSTRAINT FK_2246507B9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2246507B9D86650F ON basket (user_id_id)');
        $this->addSql('ALTER TABLE user DROP lastname, DROP name');
    }
}
