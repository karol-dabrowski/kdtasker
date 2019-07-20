<?php
declare(strict_types=1);

namespace App\Tests\Helper;

use App\Tests\ApiTester;
use App\Tests\Fixture\UserFixture;
use Faker\Factory;
use Ramsey\Uuid\Uuid;

class Api extends \Codeception\Module
{
	private const LOGIN_PATH = 'auth/login';

	/**
	 * @param ApiTester $I
	 * @return array
	 * @throws \Exception
	 */
	public function createUser(ApiTester $I): array
	{
		$faker = Factory::create();

		$userData = [
			'user_id' => Uuid::uuid4()->toString(),
			'email' => $faker->email,
			'first_name' => $faker->firstName,
			'last_name' => $faker->lastName,
			'password' => 'TestPassword123'
		];

		UserFixture::load(
			$I,
			$userData['user_id'],
			$userData['email'],
			$userData['password'],
			$userData['first_name'],
			$userData['last_name']
		);

		return $userData;
	}

	/**
	 * @param ApiTester $I
	 * @param string $email
	 * @param string $password
	 * @return string
	 */
	public function login(ApiTester $I, string $email, string $password): string
	{
		$I->haveHttpHeader('Content-Type', 'application/json');

		$loginData = [
			"username" => $email,
			"password" => $password
		];
		$I->sendPOST(self::LOGIN_PATH, $loginData);
		$response = json_decode($I->grabResponse(), true);
		return $response['token'];
	}

	/**
	 * @param ApiTester $I
	 * @return string
	 */
	public function getEventStreamName(ApiTester $I): string
	{
		$eventStreamName = $I->grabFromDatabase(
			'event_streams',
			'stream_name',
			[
				'real_stream_name' => 'event_stream'
			]
		);

		return $eventStreamName;
	}

	/**
	 * @param ApiTester $I
	 * @param string $name
	 */
	public function runProjection(ApiTester $I, string $name): void
	{
		$projectionName = $name . '_projection';
		$readModelName = $name . '_read_model';

		$projectionManager = $I->grabService('prooph_event_store.projection_manager.task_projection_manager');
		$projection = $I->grabService('prooph_event_store.projection.' . $projectionName);
		$readModel = $I->grabService('prooph_event_store.read_model.' . $readModelName);
		$readModelProjection = $projectionManager->createReadModelProjection($projectionName, $readModel);
		$projection->project($readModelProjection)->run(false);
	}
}
