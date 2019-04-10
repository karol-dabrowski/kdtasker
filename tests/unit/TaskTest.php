<?php
declare(strict_types=1);

namespace App\Tests;

use Tasker\Model\Task\Domain\Task;
use Tasker\Model\Task\Domain\TaskId;
use Tasker\Model\Task\Event\TaskCreated;

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
	 * @throws \Exception
	 */
	protected function _before()
	{
		$this->taskId = TaskId::generate();
		$this->taskTitle = "Test title";
	}

	public function testTaskCanBeCreated()
    {
		$task = Task::create($this->taskId, $this->taskTitle);
	    $this->assertSame($this->taskId, $task->taskId());
	    $this->assertSame($this->taskTitle, $task->title());

		$events = $this->tester->popRecordedEvents($task);
		$this->assertCount(1, $events);

		$event = $events[0];
		$this->assertSame(TaskCreated::class, $event->messageName());
	    $this->assertSame($this->taskId, $event->taskId());
	    $this->assertSame($this->taskTitle, $event->title());
    }
}