<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190418082158
 * @package App\Migrations
 */
final class Version20190418082158 extends AbstractMigration
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
        $this->addSql('CREATE TABLE refresh_tokens (
			id INT AUTO_INCREMENT NOT NULL, 
			refresh_token VARCHAR(128) NOT NULL, 
			username VARCHAR(255) NOT NULL, 
			valid DATETIME NOT NULL, 
			UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), 
			PRIMARY KEY(id)
		) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

	/**
	 * @param Schema $schema
	 */
    public function down(Schema $schema) : void
    {
        $this->addSql('DROP TABLE refresh_tokens');
    }
}
