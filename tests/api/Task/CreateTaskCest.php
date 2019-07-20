<?php
declare(strict_types=1);

namespace App\Tests\Api\Task;

use App\Tests\ApiTester;
use Faker\Factory;
use Faker\Generator;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;
use Tasker\Model\Task\Domain\Task;
use Tasker\Model\Task\Event\TaskCreated;

/**
 * Class CreateTaskCest
 * @package App\Tests\Api\Task
 */
class CreateTaskCest
{
	private const CREATE_TASK_PATH = 'api/task/create';

	/**
	 * @var Generator
	 */
	private $faker;

	/**
	 * @param ApiTester $I
	 */
	public function _before(ApiTester $I)
	{
		$I->haveHttpHeader('Content-Type', 'application/json');
		$this->faker = Factory::create();
	}

	/**
	 * @param ApiTester $I
	 * @throws \Exception
	 */
	public function createTaskWithoutDeadlineTime(ApiTester $I)
	{
		$userData = $I->createUser($I);
		$token = $I->login($I, $userData['email'], $userData['password']);
		$I->amBearerAuthenticated($token);

		$taskData = [
			'payload' => [
				'title' => $this->faker->realText(25),
				'task_id' => Uuid::uuid4()->toString(),
				'deadline_date' => $this->faker->date()
			]
		];

		$I->sendPOST(self::CREATE_TASK_PATH, $taskData);
		$I->seeResponseCodeIs(Response::HTTP_OK);
		$I->seeResponseContainsJson([]);

		$I->seeInDatabase(
			$I->getEventStreamName($I),
			[
				'aggregate_id' => $taskData['payload']['task_id'],
				'aggregate_type' => Task::class,
				'event_name' => TaskCreated::class
			]
		);

		$I->runProjection($I, 'open_task');
		$I->seeInCollection(
			'open_tasks_collection',
			[
				'user_id' => $userData['user_id']
			]
		);
	}
}