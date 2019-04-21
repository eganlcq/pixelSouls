<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190418203912 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fight ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE fighter ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE post ADD is_active TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE response ADD is_active TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE fight DROP is_active');
        $this->addSql('ALTER TABLE fighter DROP is_active');
        $this->addSql('ALTER TABLE post DROP is_active');
        $this->addSql('ALTER TABLE response DROP is_active');
    }
}
