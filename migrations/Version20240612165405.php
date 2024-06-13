<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240612165405 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_tag ADD tag_article_id INT NOT NULL');
        $this->addSql('ALTER TABLE article_tag ADD CONSTRAINT FK_919694F9D78F04CD FOREIGN KEY (tag_article_id) REFERENCES redaction_articles (id)');
        $this->addSql('CREATE INDEX IDX_919694F9D78F04CD ON article_tag (tag_article_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_tag DROP FOREIGN KEY FK_919694F9D78F04CD');
        $this->addSql('DROP INDEX IDX_919694F9D78F04CD ON article_tag');
        $this->addSql('ALTER TABLE article_tag DROP tag_article_id');
    }
}
