<?php
declare( strict_types=1 );

namespace Tasker\Infrastructure\Projection\Task;

use MongoDB\Database;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\AbstractReadModel;
use Tasker\Infrastructure\Projection\Table;
use Tasker\Model\Task\Event\TaskCreated;

/**
 * Class UserTasksReadModel
 * @package Tasker\Infrastructure\Projection\Task
 */
class UserTasksReadModel extends AbstractReadModel
{
	/**
	 * @var Database
	 */
	private $mongoConnection;

	/**
	 * UserTasksReadModel constructor.
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
		$this->mongoConnection->createCollection(Table::READ_MONGO_USER_TASKS);
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_USER_TASKS);
		$collection->createIndex(['user_id' => 1], ['unique' => true]);
	}

	/**
	 * @return bool
	 */
	public function isInitialized(): bool
	{
		$collections = $this->mongoConnection->listCollections();
		foreach ($collections as $collection) {
			if($collection->getName() === Table::READ_MONGO_USER_TASKS) {
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
		$userId = $event->creatorId()->toString();
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_USER_TASKS);

		if($collection->countDocuments(['user_id' => $userId]) === 0) {
			$user = [
				'user_id' => $userId,
				'tasks' => []
			];
			$collection->insertOne($user);
		}

		$task = [
			'id' => $event->taskId()->toString(),
			'title' => $event->title()
		];

		$collection->updateOne(
			[
				'user_id' => $userId
			],
			[
				'$addToSet' => [
					'tasks' => $task
				]
			]
		);
	}
}