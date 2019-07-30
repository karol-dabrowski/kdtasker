<?php
declare(strict_types=1);

namespace App\Tests\Unit\Task;

use Faker\Factory;
use Faker\Generator;
use Tasker\Model\Task\Domain\TaskDeadline;

/**
 * Class TaskDeadlineTest
 * @package App\Tests\Unit\Task
 */
class TaskDeadlineTest extends \Codeception\Test\Unit
{
	/**
	 * @var Generator
	 */
	private $faker;

	protected function _before()
	{
		$this->faker = Factory::create();
	}

	public function testTaskDeadlineWithTimeCanBeCreatedFromString()
	{
		$date = $this->faker->date();
		$time = $this->faker->time("H:i");
		$deadline = TaskDeadline::fromString($date, $time);

		$this->assertInstanceOf(TaskDeadline::class, $deadline);
		$this->assertSame($date, $deadline->dateToString());
		$this->assertSame($time, $deadline->timeToString());
	}
}