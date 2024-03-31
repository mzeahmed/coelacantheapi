<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240330150749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE capabilities (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE roles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE role_capabilities (role_id INT NOT NULL, capability_id INT NOT NULL, INDEX IDX_896D4121D60322AC (role_id), INDEX IDX_896D412192043242 (capability_id), PRIMARY KEY(role_id, capability_id))');
        $this->addSql('CREATE TABLE usermeta (id INT AUTO_INCREMENT NOT NULL, meta_key VARCHAR(255) NOT NULL, meta_value VARCHAR(255) NOT NULL, user_id INT DEFAULT NULL, INDEX IDX_718F6C44A76ED395 (user_id), PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, login VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, 2fa_token VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_roles (user_id INT NOT NULL, role_id INT NOT NULL, INDEX IDX_54FCD59FA76ED395 (user_id), INDEX IDX_54FCD59FD60322AC (role_id), PRIMARY KEY(user_id, role_id))');
        $this->addSql('ALTER TABLE role_capabilities ADD CONSTRAINT FK_896D4121D60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE role_capabilities ADD CONSTRAINT FK_896D412192043242 FOREIGN KEY (capability_id) REFERENCES capabilities (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE usermeta ADD CONSTRAINT FK_718F6C44A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FA76ED395 FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_roles ADD CONSTRAINT FK_54FCD59FD60322AC FOREIGN KEY (role_id) REFERENCES roles (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE role_capabilities DROP FOREIGN KEY FK_896D4121D60322AC');
        $this->addSql('ALTER TABLE role_capabilities DROP FOREIGN KEY FK_896D412192043242');
        $this->addSql('ALTER TABLE usermeta DROP FOREIGN KEY FK_718F6C44A76ED395');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FA76ED395');
        $this->addSql('ALTER TABLE user_roles DROP FOREIGN KEY FK_54FCD59FD60322AC');
        $this->addSql('DROP TABLE capabilities');
        $this->addSql('DROP TABLE roles');
        $this->addSql('DROP TABLE role_capabilities');
        $this->addSql('DROP TABLE usermeta');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE user_roles');
    }
}
