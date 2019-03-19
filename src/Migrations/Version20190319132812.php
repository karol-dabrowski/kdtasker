<?php
declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190319132812 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
		$this->addSql('
			CREATE TABLE `event_streams` (
				`no` BIGINT(20) NOT NULL AUTO_INCREMENT,
				`real_stream_name` VARCHAR(150) NOT NULL,
				`stream_name` CHAR(41) NOT NULL,
				`metadata` JSON,
				`category` VARCHAR(150),
				PRIMARY KEY (`no`),
				UNIQUE KEY `ix_rsn` (`real_stream_name`),
				KEY `ix_cat` (`category`)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;	
		');
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('event_streams');
    }
}
