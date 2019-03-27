<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Tasker\Model\Task\Domain\TaskId;
use Assert\Assertion;

/**
 * Class CreateTask
 * @package Tasker\Model\Task\Command
 */
class CreateTask extends Command implements PayloadConstructable
{
	use PayloadTrait;

	/**
	 * @return TaskId
	 */
	public function taskId(): TaskId
	{
		return TaskId::fromString($this->payload['task_id']);
	}

	/**
	 * @return string
	 */
	public function title(): string
	{
		return $this->payload['title'];
	}

	/**
	 * @param array $payload
	 */
	protected function setPayload(array $payload): void
	{
		Assertion::keyExists($payload, 'task_id');
		Assertion::uuid($payload['task_id']);
		Assertion::keyExists($payload, 'title');
		Assertion::string($payload['title']);
		$this->payload = $payload;
	}
}