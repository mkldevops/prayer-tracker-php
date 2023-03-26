<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210405120752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, message CLOB DEFAULT NULL, read_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, enable BOOLEAN NOT NULL, email VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_4C62E638A76ED395 ON contact (user_id)');
        $this->addSql('DROP TABLE migration_versions');
        $this->addSql('DROP INDEX IDX_B996F1013EB8070A');
        $this->addSql('DROP INDEX IDX_B996F1015E02C9F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__objective AS SELECT id, program_id, prayer_name_id, number, enable, created_at, updated_at FROM objective');
        $this->addSql('DROP TABLE objective');
        $this->addSql('CREATE TABLE objective (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, program_id INTEGER NOT NULL, prayer_name_id INTEGER NOT NULL, number INTEGER NOT NULL, enable BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, CONSTRAINT FK_B996F1013EB8070A FOREIGN KEY (program_id) REFERENCES program (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B996F1015E02C9F9 FOREIGN KEY (prayer_name_id) REFERENCES prayer_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO objective (id, program_id, prayer_name_id, number, enable, created_at, updated_at) SELECT id, program_id, prayer_name_id, number, enable, created_at, updated_at FROM __temp__objective');
        $this->addSql('DROP TABLE __temp__objective');
        $this->addSql('CREATE INDEX IDX_B996F1013EB8070A ON objective (program_id)');
        $this->addSql('CREATE INDEX IDX_B996F1015E02C9F9 ON objective (prayer_name_id)');
        $this->addSql('DROP INDEX IDX_47C953865E02C9F9');
        $this->addSql('DROP INDEX IDX_47C95386A76ED395');
        $this->addSql('DROP INDEX IDX_47C9538673484933');
        $this->addSql('CREATE TEMPORARY TABLE __temp__prayer AS SELECT id, prayer_name_id, user_id, objective_id, created_at, updated_at, accomplished_at FROM prayer');
        $this->addSql('DROP TABLE prayer');
        $this->addSql('CREATE TABLE prayer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prayer_name_id INTEGER NOT NULL, user_id INTEGER NOT NULL, objective_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, accomplished_at DATETIME DEFAULT NULL, CONSTRAINT FK_47C953865E02C9F9 FOREIGN KEY (prayer_name_id) REFERENCES prayer_name (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_47C9538673484933 FOREIGN KEY (objective_id) REFERENCES objective (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_47C95386A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO prayer (id, prayer_name_id, user_id, objective_id, created_at, updated_at, accomplished_at) SELECT id, prayer_name_id, user_id, objective_id, created_at, updated_at, accomplished_at FROM __temp__prayer');
        $this->addSql('DROP TABLE __temp__prayer');
        $this->addSql('CREATE INDEX IDX_47C953865E02C9F9 ON prayer (prayer_name_id)');
        $this->addSql('CREATE INDEX IDX_47C95386A76ED395 ON prayer (user_id)');
        $this->addSql('CREATE INDEX IDX_47C9538673484933 ON prayer (objective_id)');
        $this->addSql('DROP INDEX IDX_92ED7784A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__program AS SELECT id, user_id, name, enable, created_at, updated_at, day_objective FROM program');
        $this->addSql('DROP TABLE program');
        $this->addSql('CREATE TABLE program (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, name VARCHAR(255) DEFAULT NULL COLLATE BINARY, enable BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, day_objective INTEGER DEFAULT NULL, CONSTRAINT FK_92ED7784A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO program (id, user_id, name, enable, created_at, updated_at, day_objective) SELECT id, user_id, name, enable, created_at, updated_at, day_objective FROM __temp__program');
        $this->addSql('DROP TABLE __temp__program');
        $this->addSql('CREATE INDEX IDX_92ED7784A76ED395 ON program (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE migration_versions (version VARCHAR(14) NOT NULL COLLATE BINARY, executed_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , PRIMARY KEY(version))');
        $this->addSql('DROP TABLE contact');
        $this->addSql('DROP INDEX IDX_B996F1013EB8070A');
        $this->addSql('DROP INDEX IDX_B996F1015E02C9F9');
        $this->addSql('CREATE TEMPORARY TABLE __temp__objective AS SELECT id, program_id, prayer_name_id, number, enable, created_at, updated_at FROM objective');
        $this->addSql('DROP TABLE objective');
        $this->addSql('CREATE TABLE objective (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, program_id INTEGER NOT NULL, prayer_name_id INTEGER NOT NULL, number INTEGER NOT NULL, enable BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO objective (id, program_id, prayer_name_id, number, enable, created_at, updated_at) SELECT id, program_id, prayer_name_id, number, enable, created_at, updated_at FROM __temp__objective');
        $this->addSql('DROP TABLE __temp__objective');
        $this->addSql('CREATE INDEX IDX_B996F1013EB8070A ON objective (program_id)');
        $this->addSql('CREATE INDEX IDX_B996F1015E02C9F9 ON objective (prayer_name_id)');
        $this->addSql('DROP INDEX IDX_47C953865E02C9F9');
        $this->addSql('DROP INDEX IDX_47C9538673484933');
        $this->addSql('DROP INDEX IDX_47C95386A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__prayer AS SELECT id, prayer_name_id, objective_id, user_id, accomplished_at, created_at, updated_at FROM prayer');
        $this->addSql('DROP TABLE prayer');
        $this->addSql('CREATE TABLE prayer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, prayer_name_id INTEGER NOT NULL, objective_id INTEGER DEFAULT NULL, user_id INTEGER NOT NULL, accomplished_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO prayer (id, prayer_name_id, objective_id, user_id, accomplished_at, created_at, updated_at) SELECT id, prayer_name_id, objective_id, user_id, accomplished_at, created_at, updated_at FROM __temp__prayer');
        $this->addSql('DROP TABLE __temp__prayer');
        $this->addSql('CREATE INDEX IDX_47C953865E02C9F9 ON prayer (prayer_name_id)');
        $this->addSql('CREATE INDEX IDX_47C9538673484933 ON prayer (objective_id)');
        $this->addSql('CREATE INDEX IDX_47C95386A76ED395 ON prayer (user_id)');
        $this->addSql('DROP INDEX IDX_92ED7784A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__program AS SELECT id, user_id, day_objective, name, enable, created_at, updated_at FROM program');
        $this->addSql('DROP TABLE program');
        $this->addSql('CREATE TABLE program (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, day_objective INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, enable BOOLEAN NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO program (id, user_id, day_objective, name, enable, created_at, updated_at) SELECT id, user_id, day_objective, name, enable, created_at, updated_at FROM __temp__program');
        $this->addSql('DROP TABLE __temp__program');
        $this->addSql('CREATE INDEX IDX_92ED7784A76ED395 ON program (user_id)');
    }
}