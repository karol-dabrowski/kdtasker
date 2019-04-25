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
	 * @var array
	 */
	private $userData;

	/**
	 * @param ApiTester $I
	 * @throws \Exception
	 */
	public function _before(ApiTester $I)
	{
		$I->haveHttpHeader('Content-Type', 'application/json');

		$faker = Factory::create();
		$userId = Uuid::uuid4();
		$email = $faker->email;
		$firstName = $faker->firstName;
		$lastName = $faker->lastName;
		$password = 'TestPassword123';

		$this->userData = [
			'payload' => [
				'user_id' => $userId->toString(),
				'email' => $email,
				'first_name' => $firstName,
				'last_name' => $lastName,
				'password' => $password
			]
		];
	}

	/**
	 * @param ApiTester $I
	 * @throws \Exception
	 */
	public function registerWithCorrectData(ApiTester $I)
	{
		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_OK);
		$I->seeResponseContainsJson([]);

		$I->seeInDatabase(
			'users',
			[
				'id' => $this->userData['payload']['user_id'],
				'email' => $this->userData['payload']['email'],
				'first_name' => $this->userData['payload']['first_name'],
				'last_name' => $this->userData['payload']['last_name'],
				'confirmed' => 0
			]
		);
	}
}