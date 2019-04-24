<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190424132156
 * @package App\Migrations
 */
final class Version20190424132156 extends AbstractMigration
{
	/**
	 * @return string
	 */
    public function getDescription() : string
    {
        return '';
    }

	/**
	 * @param Schema $schema
	 */
    public function up(Schema $schema) : void
    {
	    $this->addSql('CREATE UNIQUE INDEX users_email_uindex ON users (email)');
    }

	/**
	 * @param Schema $schema
	 */
    public function down(Schema $schema) : void
    {
	    $this->addSql('DROP INDEX users_email_uindex ON users');
    }
}
