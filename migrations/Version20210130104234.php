<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130104234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_bot_session (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(39) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_bot_session_message (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, related_id INT DEFAULT NULL, content VARCHAR(500) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, is_response TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CE1AE634613FECDF (session_id), UNIQUE INDEX UNIQ_CE1AE6344162C001 (related_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_bot_session_message ADD CONSTRAINT FK_CE1AE634613FECDF FOREIGN KEY (session_id) REFERENCES chat_bot_session (id)');
        $this->addSql('ALTER TABLE chat_bot_session_message ADD CONSTRAINT FK_CE1AE6344162C001 FOREIGN KEY (related_id) REFERENCES chat_bot_session_message (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_bot_session_message DROP FOREIGN KEY FK_CE1AE634613FECDF');
        $this->addSql('ALTER TABLE chat_bot_session_message DROP FOREIGN KEY FK_CE1AE6344162C001');
        $this->addSql('DROP TABLE chat_bot_session');
        $this->addSql('DROP TABLE chat_bot_session_message');
    }
}
