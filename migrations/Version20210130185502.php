<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130185502 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_bot_session_message DROP FOREIGN KEY FK_CE1AE634613FECDF');
        $this->addSql('ALTER TABLE chat_bot_session_message DROP FOREIGN KEY FK_CE1AE6344162C001');
        $this->addSql('CREATE TABLE chat_bot_conversation (id INT AUTO_INCREMENT NOT NULL, remote_token VARCHAR(256) DEFAULT NULL, remote_id VARCHAR(32) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE chat_bot_conversation_message (id INT AUTO_INCREMENT NOT NULL, conversation_id INT DEFAULT NULL, related_id INT DEFAULT NULL, content VARCHAR(500) NOT NULL, avatar VARCHAR(255) DEFAULT NULL, is_response TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_F4BBB4F29AC0396 (conversation_id), UNIQUE INDEX UNIQ_F4BBB4F24162C001 (related_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE chat_bot_conversation_message ADD CONSTRAINT FK_F4BBB4F29AC0396 FOREIGN KEY (conversation_id) REFERENCES chat_bot_conversation (id)');
        $this->addSql('ALTER TABLE chat_bot_conversation_message ADD CONSTRAINT FK_F4BBB4F24162C001 FOREIGN KEY (related_id) REFERENCES chat_bot_conversation_message (id)');
        $this->addSql('DROP TABLE chat_bot_session');
        $this->addSql('DROP TABLE chat_bot_session_message');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chat_bot_conversation_message DROP FOREIGN KEY FK_F4BBB4F29AC0396');
        $this->addSql('ALTER TABLE chat_bot_conversation_message DROP FOREIGN KEY FK_F4BBB4F24162C001');
        $this->addSql('CREATE TABLE chat_bot_session (id INT AUTO_INCREMENT NOT NULL, ip VARCHAR(39) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE chat_bot_session_message (id INT AUTO_INCREMENT NOT NULL, session_id INT DEFAULT NULL, related_id INT DEFAULT NULL, content VARCHAR(500) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, avatar VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, is_response TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_CE1AE634613FECDF (session_id), UNIQUE INDEX UNIQ_CE1AE6344162C001 (related_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE chat_bot_session_message ADD CONSTRAINT FK_CE1AE634613FECDF FOREIGN KEY (session_id) REFERENCES chat_bot_session (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE chat_bot_session_message ADD CONSTRAINT FK_CE1AE6344162C001 FOREIGN KEY (related_id) REFERENCES chat_bot_session_message (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE chat_bot_conversation');
        $this->addSql('DROP TABLE chat_bot_conversation_message');
    }
}
