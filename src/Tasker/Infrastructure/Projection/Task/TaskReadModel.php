<?php
declare( strict_types=1 );

namespace Tasker\Infrastructure\Projection\Task;

use MongoDB\Collection;
use MongoDB\Database;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\AbstractReadModel;
use Tasker\Infrastructure\Projection\Table;
use Tasker\Model\Task\Domain\TaskStatus;
use Tasker\Model\Task\Event\TaskCompleted;
use Tasker\Model\Task\Event\TaskCreated;
use Tasker\Model\User\Domain\UserId;

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
	 * @throws \Exception
	 */
	public function __invoke(AggregateChanged $event)
	{
		switch(true) {
			case $event instanceof TaskCreated:
				$this->insertTask($event);
				break;
			case $event instanceof TaskCompleted:
				$this->markTaskAsCompleted($event);
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
		$this->mongoConnection->dropCollection(Table::READ_MONGO_TASKS);
		$this->init();
	}

	public function delete(): void
	{
		$this->mongoConnection->dropCollection(Table::READ_MONGO_TASKS);
	}

	/**
	 * @param TaskCreated $event
	 * @throws \Exception
	 */
	private function insertTask(TaskCreated $event)
	{
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_TASKS);

		$userId = $event->creatorId();
		$taskId = $event->taskId();
		$deadlineDate = $event->deadline()->dateToString();
		$deadlineTime = $event->deadline()->timeToString();
		$title = $event->title();

		if($collection->countDocuments(['user_id' => $userId->toString()]) === 0) {
			$this->createUserDocument($collection, $userId);
		}

		$task = [
			'task_id' => $taskId->toString(),
			'deadline_date' => $deadlineDate,
			'deadline_time' => $deadlineTime,
			'title' => $title,
			'status' => TaskStatus::OPEN
		];

		$collection->updateOne(
			[
				'user_id' => $userId->toString()
			],
			[
				'$addToSet' => [
					'days' => $task
				]
			]
		);
	}

	/**
	 * @param TaskCompleted $event
	 */
	private function markTaskAsCompleted(TaskCompleted $event)
	{
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_TASKS);

		$taskId = $event->taskId();

		$collection->updateOne(
			[
				'days' => [
					'$elemMatch' => [
						'task_id' => $taskId->toString()
					]
				]
			],
			[
				'$set' => [
					'days.$.status' => TaskStatus::COMPLETED
				]
			]
		);
	}

	/**
	 * @param Collection $collection
	 * @param UserId $userId
	 */
	private function createUserDocument(Collection $collection, UserId $userId): void
	{
		$user = [
			'user_id' => $userId->toString(),
			'days' => []
		];

		$collection->insertOne($user);
	}
}