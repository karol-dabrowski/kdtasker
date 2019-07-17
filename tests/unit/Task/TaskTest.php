<?php
declare(strict_types=1);

namespace App\Tests\Unit\Task;

use Faker\Factory;
use Faker\Generator;
use Tasker\Model\Task\Domain\Task;
use Tasker\Model\Task\Domain\TaskDeadline;
use Tasker\Model\Task\Domain\TaskId;
use Tasker\Model\Task\Event\TaskCreated;
use Tasker\Model\User\Domain\UserId;

/**
 * Class TaskTest
 * @package App\Tests\Unit\Task
 */
class TaskTest extends \Codeception\Test\Unit
{
    /**
     * @var \App\Tests\UnitTester
     */
    protected $tester;

	/**
	 * @var Generator
	 */
	private $faker;

	/**
	 * @var TaskId
	 */
    private $taskId;

	/**
	 * @var string
	 */
    private $taskTitle;

	/**
	 * @var UserId
	 */
	private $creatorId;

	/**
	 * @var UserId
	 */
	private $assigneeId;

	/**
	 * @var TaskDeadline
	 */
	private $deadlineWithoutTime;

	/**
	 * @var TaskDeadline
	 */
	private $deadlineWithTime;

	/**
	 * @throws \Exception
	 */
	protected function _before()
	{
		$this->faker = Factory::create();
		$this->taskId = TaskId::generate();
		$this->taskTitle = "Test title";
		$this->creatorId = UserId::generate();
		$this->assigneeId = UserId::generate();
		$this->deadlineWithoutTime = TaskDeadline::fromString($this->faker->date(), null);
		$this->deadlineWithTime = TaskDeadline::fromString($this->faker->date(), $this->faker->time("H:i"));
	}

	public function testTaskWithoutTimeCanBeCreated()
    {
		$task = Task::create($this->taskId, $this->taskTitle, $this->creatorId, $this->assigneeId, $this->deadlineWithoutTime);
	    $this->assertSame($this->taskId, $task->taskId());
	    $this->assertSame($this->taskTitle, $task->title());
	    $this->assertSame($this->creatorId, $task->creatorId());
	    $this->assertSame($this->assigneeId, $task->assigneeId());
	    $this->assertSame($this->deadlineWithoutTime, $task->deadline());
	    $this->assertInstanceOf(Task::class, $task);

		$events = $this->tester->popRecordedEvents($task);
		$this->assertCount(1, $events);

		$event = $events[0];
		$this->assertInstanceOf(TaskCreated::class, $event);
	    $this->assertSame($this->taskId, $event->taskId());
	    $this->assertSame($this->taskTitle, $event->title());
		$this->assertSame($this->creatorId, $event->creatorId());
		$this->assertSame($this->assigneeId, $event->assigneeId());
		$this->assertSame($this->deadlineWithoutTime, $event->deadline());
    }

	public function testTaskWithTimeCanBeCreated()
	{
		$task = Task::create($this->taskId, $this->taskTitle, $this->creatorId, $this->assigneeId, $this->deadlineWithTime);
		$this->assertSame($this->taskId, $task->taskId());
		$this->assertSame($this->taskTitle, $task->title());
		$this->assertSame($this->creatorId, $task->creatorId());
		$this->assertSame($this->assigneeId, $task->assigneeId());
		$this->assertSame($this->deadlineWithTime, $task->deadline());
		$this->assertInstanceOf(Task::class, $task);

		$events = $this->tester->popRecordedEvents($task);
		$this->assertCount(1, $events);

		$event = $events[0];
		$this->assertInstanceOf(TaskCreated::class, $event);
		$this->assertSame($this->taskId, $event->taskId());
		$this->assertSame($this->taskTitle, $event->title());
		$this->assertSame($this->creatorId, $event->creatorId());
		$this->assertSame($this->assigneeId, $event->assigneeId());
		$this->assertSame($this->deadlineWithTime, $event->deadline());
	}
}