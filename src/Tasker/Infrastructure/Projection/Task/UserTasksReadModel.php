<?php
declare( strict_types=1 );

namespace Tasker\Infrastructure\Projection\Task;

use MongoDB\Collection;
use MongoDB\Database;
use Prooph\EventSourcing\AggregateChanged;
use Prooph\EventStore\Projection\AbstractReadModel;
use Tasker\Infrastructure\Projection\Table;
use Tasker\Model\Task\Event\TaskCreated;
use Tasker\Model\User\Domain\UserId;

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
	 * @throws \Exception
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
	 * @throws \Exception
	 */
	private function insertTask(TaskCreated $event)
	{
		$userId = $event->creatorId();
		$deadlineDate = $event->deadline()->dateToString();
		$collection = $this->mongoConnection->selectCollection(Table::READ_MONGO_USER_TASKS);

		if($collection->countDocuments(['user_id' => $userId->toString()]) === 0) {
			$this->createUserDocument($collection, $userId);
		}

		$userDateFilter = [
			'user_id' => $userId->toString(),
			'days' => [
				'$elemMatch' => [
					'date' => $deadlineDate
				]
			]
		];

		if($collection->countDocuments($userDateFilter) === 0) {
			$this->createUserDayDocument($collection, $userId, $deadlineDate);
		}

		$task = [
			'id' => $event->taskId()->toString(),
		    'title' => $event->title(),
			'time' => $event->deadline()->timeToString()
		];

		$collection->updateOne(
			$userDateFilter,
			[
				'$push' => [
					'days.$.tasks_list' => [
						'$each' => [$task],
						'$sort' => [
							'time' => 1
						]
					]
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

	/**
	 * @param Collection $collection
	 * @param UserId $userId
	 * @param string $deadlineDate
	 */
	private function createUserDayDocument(Collection $collection, UserId $userId, string $deadlineDate): void
	{
		$collection->updateOne(
			[
				'user_id' => $userId->toString()
			],
			[
				'$addToSet' => [
					'days' => [
						'date' => $deadlineDate,
						'tasks_list' => []
					]
				]
			]
		);
	}
}