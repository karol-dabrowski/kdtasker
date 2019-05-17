<?php
declare( strict_types=1 );

namespace Tasker\Infrastructure\Projection\Task;

use MongoDB\Database;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\AbstractReadModel;
use Tasker\Infrastructure\Projection\Table;
use Tasker\Model\Task\Event\TaskCreated;

/**
 * Class TaskReadModel
 * @package Tasker\Infrastructure\Projection\Task
 */
class TaskReadModel extends AbstractReadModel
{
	/**
	 * @var Database
	 */
	private $mongoConnection;

	/**
	 * TaskReadModel constructor.
	 * @param Database $mongoConnection
	 */
	public function __construct(Database $mongoConnection)
	{
		$this->mongoConnection = $mongoConnection;
	}

	/**
	 * @param AggregateChanged $event
	 */
	public function __invoke(AggregateChanged $event)
	{
		switch(true) {
			case $event instanceof TaskCreated:
				$this->insertTask($event);
				break;
		}
	}

	public function init(): void
	{
		$this->mongoConnection->createCollection(Table::READ_MONGO_TASKS);
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_TASKS);
		$collection->createIndex(['task_id' => 1], ['unique' => true]);
	}

	/**
	 * @return bool
	 */
	public function isInitialized(): bool
	{
		$collections = $this->mongoConnection->listCollections();
		foreach ($collections as $collection) {
			if($collection->getName() === Table::READ_MONGO_TASKS) {
				return true;
			}
		}

		return false;
	}

	public function reset(): void
	{
		// TODO: Implement reset() method.
	}

	public function delete(): void
	{
		// TODO: Implement delete() method.
	}

	/**
	 * @param TaskCreated $event
	 */
	private function insertTask(TaskCreated $event)
	{
		$task = [
			'task_id' => $event->taskId()->toString(),
			'current_state' => [
				'title' => $event->title()
			]
		];

		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_TASKS);

		if($collection->countDocuments(['task_id' => $task['task_id']]) === 0) {
			$collection->insertOne($task);
		}
	}
}