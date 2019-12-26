<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191226064331 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE customers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE tasks CHANGE task_name task_name VARCHAR(255) NOT NULL, CHANGE task_status task_status VARCHAR(25) DEFAULT NULL, CHANGE date_of_added date_of_added DATETIME NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE user_email user_email VARCHAR(255) NOT NULL, CHANGE user_password user_password VARCHAR(255) NOT NULL, CHANGE date_of_added date_of_added DATE NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE customers');
        $this->addSql('ALTER TABLE tasks CHANGE task_name task_name VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE task_status task_status VARCHAR(25) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date_of_added date_of_added DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE user_email user_email VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE user_password user_password VARCHAR(250) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, CHANGE date_of_added date_of_added DATETIME DEFAULT \'current_timestamp()\' NOT NULL');
    }
}
