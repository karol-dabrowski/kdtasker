<?php
declare(strict_types=1);

namespace App\Tests\Api\User;

use App\Tests\ApiTester;
use Faker\Factory;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class RegisterCest
 * @package App\Tests\Api\User
 */
class RegisterCest
{
	private const REGISTER_PATH = 'auth/register';

	/**
	 * @param ApiTester $I
	 * @throws \Exception
	 */
	public function registerWithCorrectData(ApiTester $I)
	{

		$I->haveHttpHeader('Content-Type', 'application/json');

		$faker = Factory::create();
		$email = $faker->email;
		$firstName = $faker->firstName;
		$lastName = $faker->lastName;
		$password = 'TestPassword123';

		$data = [
			'payload' => [
				'user_id' => Uuid::uuid4()->toString(),
				'email' => $email,
				'first_name' => $firstName,
				'last_name' => $lastName,
				'password' => $password
			]
		];

		$I->sendPOST(self::REGISTER_PATH, $data);
		$I->seeResponseCodeIs(Response::HTTP_OK);
		$I->seeResponseContainsJson([]);

		$I->seeInDatabase(
			'users',
			[
				'email' => $email,
				'first_name' => $firstName,
				'last_name' => $lastName
			]
		);
	}
}