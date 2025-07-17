<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117092300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create all tables for PostgreSQL';
    }

    public function up(Schema $schema): void
    {
        // Create user table
        $this->addSql('CREATE TABLE "user" (
            id SERIAL PRIMARY KEY,
            email VARCHAR(180) NOT NULL,
            roles JSON NOT NULL,
            password VARCHAR(255) NOT NULL,
            name VARCHAR(255) NOT NULL,
            lastname VARCHAR(255) NOT NULL,
            presentation TEXT NOT NULL,
            phone VARCHAR(10) NOT NULL,
            address VARCHAR(255) NOT NULL,
            city VARCHAR(255) NOT NULL,
            zipcode INTEGER NOT NULL
        )');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON "user" (email)');

        // Create education table
        $this->addSql('CREATE TABLE education (
            id SERIAL PRIMARY KEY,
            school VARCHAR(255) NOT NULL,
            degree VARCHAR(255) NOT NULL,
            field_of_study VARCHAR(255) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE DEFAULT NULL,
            description TEXT DEFAULT NULL
        )');

        // Create skills table
        $this->addSql('CREATE TABLE skills (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            icon VARCHAR(255) NOT NULL,
            icon_name VARCHAR(255) NOT NULL,
            thumbnail VARCHAR(255) NOT NULL,
            thumbnail_name VARCHAR(255) NOT NULL,
            description TEXT DEFAULT NULL
        )');

        // Create socials_networks table
        $this->addSql('CREATE TABLE socials_networks (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            url VARCHAR(255) NOT NULL,
            icon VARCHAR(255) NOT NULL,
            icon_name VARCHAR(255) NOT NULL
        )');

        // Create works table
        $this->addSql('CREATE TABLE works (
            id SERIAL PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            description TEXT NOT NULL,
            image VARCHAR(255) NOT NULL,
            image_name VARCHAR(255) NOT NULL,
            url VARCHAR(255) DEFAULT NULL,
            technologies TEXT DEFAULT NULL,
            start_date DATE NOT NULL,
            end_date DATE DEFAULT NULL
        )');

        // Create contact_entry table
        $this->addSql('CREATE TABLE contact_entry (
            id SERIAL PRIMARY KEY,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            message TEXT NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL
        )');
        $this->addSql('COMMENT ON COLUMN contact_entry.created_at IS \'(DC2Type:datetime_immutable)\'');

        // Create messenger_messages table
        $this->addSql('CREATE TABLE messenger_messages (
            id BIGSERIAL PRIMARY KEY,
            body TEXT NOT NULL,
            headers TEXT NOT NULL,
            queue_name VARCHAR(190) NOT NULL,
            created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            available_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL,
            delivered_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL
        )');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('COMMENT ON COLUMN messenger_messages.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.available_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN messenger_messages.delivered_at IS \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE contact_entry');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('DROP TABLE works');
        $this->addSql('DROP TABLE socials_networks');
        $this->addSql('DROP TABLE skills');
        $this->addSql('DROP TABLE education');
        $this->addSql('DROP TABLE "user"');
    }
}