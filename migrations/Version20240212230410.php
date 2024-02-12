<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240212230410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, message CLOB DEFAULT NULL, read_at DATETIME DEFAULT NULL, email VARCHAR(255) NOT NULL, enable BOOLEAN DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_4C62E638A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_4C62E638A76ED395 ON contact (user_id)');
        $this->addSql('CREATE TABLE objective (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, program_id INTEGER NOT NULL, prayer_name_id INTEGER NOT NULL, number INTEGER NOT NULL, enable BOOLEAN DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_B996F1013EB8070A FOREIGN KEY (program_id) REFERENCES program (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B996F1015E02C9F9 FOREIGN KEY (prayer_name_id) REFERENCES prayer_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_B996F1013EB8070A ON objective (program_id)');
        $this->addSql('CREATE INDEX IDX_B996F1015E02C9F9 ON objective (prayer_name_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B996F1013EB8070A5E02C9F9 ON objective (program_id, prayer_name_id)');
        $this->addSql('CREATE TABLE prayer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prayer_name_id INTEGER NOT NULL, objective_id INTEGER DEFAULT NULL, user_id INTEGER NOT NULL, accomplished_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_47C953865E02C9F9 FOREIGN KEY (prayer_name_id) REFERENCES prayer_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_47C9538673484933 FOREIGN KEY (objective_id) REFERENCES objective (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_47C95386A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_47C953865E02C9F9 ON prayer (prayer_name_id)');
        $this->addSql('CREATE INDEX IDX_47C9538673484933 ON prayer (objective_id)');
        $this->addSql('CREATE INDEX IDX_47C95386A76ED395 ON prayer (user_id)');
        $this->addSql('CREATE TABLE prayer_name (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, enable BOOLEAN DEFAULT 1 NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE TABLE program (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, day_objective INTEGER DEFAULT NULL, enable BOOLEAN DEFAULT 1 NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_92ED7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_92ED7784A76ED395 ON program (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, enable BOOLEAN DEFAULT 1 NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP TABLE objective');
        $this->addSql('DROP TABLE prayer');
        $this->addSql('DROP TABLE prayer_name');
        $this->addSql('DROP TABLE program');
        $this->addSql('DROP TABLE user');
    }
}
