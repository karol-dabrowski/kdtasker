<?php
declare(strict_types=1);

namespace Tasker\Model\Task\Domain;

/**
 * Class TaskDeadline
 * @package Tasker\Model\Task\Domain
 */
class TaskDeadline
{
	/**
	 * @var \DateTime
	 */
	private $deadline;

	/**
	 * @var bool
	 */
	private $onlyDate;

	/**
	 * @param string $date
	 * @param string $time
	 * @return TaskDeadline
	 * @throws \Exception
	 */
	public static function fromString(string $date, ?string $time): TaskDeadline
	{
		if(is_null($time)) {
			return new self(new \DateTime($date), true);
		}

		return new self(new \DateTime($date . ' ' . $time));
	}

	/**
	 * TaskDeadline constructor.
	 * @param \DateTime $deadline
	 * @param bool $onlyDate
	 */
	private function __construct(\DateTime $deadline, bool $onlyDate = false)
	{
		$this->deadline = $deadline;
		$this->onlyDate = $onlyDate;
	}

	/**
	 * @return string
	 */
	public function dateToString(): string
	{
		return $this->deadline->format('Y-m-d');
	}

	/**
	 * @return string|null
	 */
	public function timeToString(): ?string
	{
		return $this->onlyDate ? null : $this->deadline->format('H:i');
	}
}