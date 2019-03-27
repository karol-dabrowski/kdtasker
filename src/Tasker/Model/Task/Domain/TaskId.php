<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Class TaskId
 * @package Tasker\Model\Task\Domain
 */
class TaskId
{
	/**
	 * @var UuidInterface
	 */
	private $uuid;

	/**
	 * TaskId constructor.
	 * @param UuidInterface $uuid
	 */
	private function __construct(UuidInterface $uuid)
	{
		$this->uuid = $uuid;
	}

	/**
	 * @return TaskId
	 * @throws \Exception
	 */
	public static function generate(): TaskId
	{
		return new self(Uuid::uuid4());
	}

	/**
	 * @param string $taskId
	 * @return TaskId
	 */
	public static function fromString(string $taskId): TaskId
	{
		return new self(Uuid::fromString($taskId));
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->uuid->toString();
	}
}