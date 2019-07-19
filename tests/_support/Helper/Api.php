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
}
