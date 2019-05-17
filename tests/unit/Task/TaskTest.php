<?php
declare(strict_types=1);

namespace App\Tests\Unit\Task;

use Tasker\Model\Task\Domain\Task;
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
	 * @throws \Exception
	 */
	protected function _before()
	{
		$this->taskId = TaskId::generate();
		$this->taskTitle = "Test title";
		$this->creatorId = UserId::generate();
		$this->assigneeId = UserId::generate();

	}

	public function testTaskCanBeCreated()
    {
		$task = Task::create($this->taskId, $this->taskTitle, $this->creatorId, $this->assigneeId);
	    $this->assertSame($this->taskId, $task->taskId());
	    $this->assertSame($this->taskTitle, $task->title());
	    $this->assertSame($this->creatorId, $task->creatorId());
	    $this->assertSame($this->assigneeId, $task->assigneeId());
	    $this->assertInstanceOf(Task::class, $task);

		$events = $this->tester->popRecordedEvents($task);
		$this->assertCount(1, $events);

		$event = $events[0];
		$this->assertInstanceOf(TaskCreated::class, $event);
	    $this->assertSame($this->taskId, $event->taskId());
	    $this->assertSame($this->taskTitle, $event->title());
		$this->assertSame($this->creatorId, $event->creatorId());
		$this->assertSame($this->assigneeId, $event->assigneeId());
    }
}