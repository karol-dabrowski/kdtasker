<?php
declare(strict_types=1);

namespace Tasker\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Tasker\Model\Task\Domain\TaskId;

trait IdTrait
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
	public static function generate(): Id
	{
		return new self(Uuid::uuid4());
	}

	/**
	 * @param string $taskId
	 * @return TaskId
	 */
	public static function fromString(string $taskId): Id
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

	/**
	 * @param Id $other
	 * @return bool
	 */
	public function isEqual(Id $other): bool
	{
		return \get_class($this) === \get_class($other) && $this->uuid->equals($other->uuid);
	}
}