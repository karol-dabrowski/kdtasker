<?php
declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Class Version20190510171259
 * @package App\Migrations
 */
final class Version20190510171259 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
	public function up(Schema $schema) : void
	{
		$this->addSql('
			CREATE TABLE `projections` (
				`no` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`name` VARCHAR(150) NOT NULL,
				`position` JSON,
				`state` JSON,
				`status` VARCHAR(28) NOT NULL,
				`locked_until` CHAR(26),
				PRIMARY KEY (`no`),
				UNIQUE KEY `ix_name` (`name`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
		');
	}

	/**
	 * @param Schema $schema
	 */
	public function down(Schema $schema) : void
	{
		$schema->dropTable('projections');
	}
}
