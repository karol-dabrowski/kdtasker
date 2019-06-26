<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Tasker\Model\Task\Domain\TaskId;
use Assert\Assertion;

/**
 * Class DeleteTask
 * @package Tasker\Model\Task\Command
 */
class DeleteTask extends Command implements PayloadConstructable
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
	 * @param array $payload
	 */
	protected function setPayload(array $payload): void
	{
		Assertion::keyExists($payload, 'task_id', 'task_id|is_required');
		Assertion::uuid($payload['task_id'], 'task_id|must_be_correct_uuid');

		$this->payload = $payload;
	}
}