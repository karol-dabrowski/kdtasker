<?php

declare(strict_types=1);

namespace App\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Tasker\Infrastructure\Projection\Table;

/**
 * Class Version20190410104232
 * @package App\Migrations
 */
final class Version20190410104232 extends AbstractMigration
{
	/**
	 * @param Schema $schema
	 */
    public function up(Schema $schema) : void
    {
		$table = $schema->createTable(Table::USERS);

		$table->addColumn('id', 'uuid');
	    $table->addColumn('email', 'string', ['length' => 100]);
	    $table->addColumn('password', 'string');
	    $table->addColumn('first_name', 'string', ['length' => 80]);
	    $table->addColumn('last_name', 'string', ['length' => 120]);
	    $table->addColumn('confirmed', 'boolean', ['default' => 0]);
		$table->addColumn('created', 'datetime_immutable', ['notnull' => false, 'default' => null]);
	    $table->addColumn('modified', 'datetime', ['notnull' => false, 'default' => null]);

	    $table->setPrimaryKey(['id']);
    }

	/**
	 * @param Schema $schema
	 */
    public function down(Schema $schema) : void
    {
	    $schema->dropTable(Table::USERS);
    }
}
