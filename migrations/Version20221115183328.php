<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221115183328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT fk_62dc90f39d86650f');
        $this->addSql('DROP INDEX idx_62dc90f39d86650f');
        $this->addSql('ALTER TABLE pokemon RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_62DC90F3A76ED395 ON pokemon (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F3A76ED395');
        $this->addSql('DROP INDEX IDX_62DC90F3A76ED395');
        $this->addSql('ALTER TABLE pokemon RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT fk_62dc90f39d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_62dc90f39d86650f ON pokemon (user_id_id)');
    }
}
