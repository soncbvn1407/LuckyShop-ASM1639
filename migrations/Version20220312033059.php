<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220312033059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mobile DROP FOREIGN KEY FK_3C7323E0986414FE');
        $this->addSql('DROP TABLE `from`');
        $this->addSql('DROP TABLE mobile_from');
        $this->addSql('DROP INDEX IDX_3C7323E0986414FE ON mobile');
        $this->addSql('ALTER TABLE mobile CHANGE mobile_from_id total INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `from` (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE mobile_from (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE mobile CHANGE total mobile_from_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE mobile ADD CONSTRAINT FK_3C7323E0986414FE FOREIGN KEY (mobile_from_id) REFERENCES mobile_from (id)');
        $this->addSql('CREATE INDEX IDX_3C7323E0986414FE ON mobile (mobile_from_id)');
    }
}
