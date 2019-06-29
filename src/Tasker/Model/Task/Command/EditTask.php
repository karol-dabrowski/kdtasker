<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Command;

use Prooph\Common\Messaging\Command;
use Prooph\Common\Messaging\PayloadConstructable;
use Prooph\Common\Messaging\PayloadTrait;
use Tasker\Model\Task\Domain\TaskDeadline;
use Tasker\Model\Task\Domain\TaskId;
use Assert\Assertion;

/**
 * Class EditTask
 * @package Tasker\Model\Task\Command
 */
class EditTask extends Command implements PayloadConstructable
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
	 * @return TaskDeadline
	 * @throws \Exception
	 */
	public function deadline(): TaskDeadline
	{
		$deadlineTime = isset($this->payload['deadline_time']) ? $this->payload['deadline_time'] : null;
		return TaskDeadline::fromString($this->payload['deadline_date'], $deadlineTime);
	}

	/**
	 * @param array $payload
	 */
	protected function setPayload(array $payload): void
	{
		Assertion::keyExists($payload, 'task_id', 'task_id|is_required');
		Assertion::uuid($payload['task_id'], 'task_id|must_be_correct_uuid');
		Assertion::keyExists($payload, 'title', 'title|is_required');
		Assertion::string($payload['title'], 'title|must_be_a_string');
		Assertion::minLength($payload['title'], 3, 'title|too_short');
		Assertion::maxLength($payload['title'], 255, 'title|too_long');
		Assertion::keyExists($payload, 'deadline_date', 'deadline_date|is_required');
		Assertion::date($payload['deadline_date'], 'Y-m-d', 'deadline_date|is_incorrect');

		if(isset($payload['deadline_time'])) {
			Assertion::date($payload['deadline_time'], 'H:i', 'deadline_time|is_incorrect');
		}

		$this->payload = $payload;
	}
}