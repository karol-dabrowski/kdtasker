<?php
declare(strict_types=1);

namespace App\Tests\Api\User;

use App\Tests\ApiTester;
use App\Tests\Fixture\UserFixture;
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

	/**
	 * @param ApiTester $I
	 */
	public function registerWithoutId(ApiTester $I)
	{
		unset($this->userData['payload']['user_id']);

		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
		$I->seeResponseContainsJson(
			$this->createErrorJsonResponse('validation_error', 'user_id', 'is_required')
		);
	}

	/**
	 * @param ApiTester $I
	 */
	public function registerWithIncorrectId(ApiTester $I)
	{
		$this->userData['payload']['user_id'] = 'incorrect-user-id';

		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
		$I->seeResponseContainsJson(
			$this->createErrorJsonResponse('validation_error', 'user_id', 'must_be_correct_uuid')
		);
	}

	/**
	 * @param ApiTester $I
	 * @throws \Exception
	 */
	public function registerWithAlreadyUsedId(ApiTester $I)
	{
		UserFixture::load(
			$I,
			$this->userData['payload']['user_id'],
			$this->userData['payload']['email'],
			$this->userData['payload']['password'],
			$this->userData['payload']['first_name'],
			$this->userData['payload']['last_name']
		);

		$this->userData['payload']['email'] = Factory::create()->email;
		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
		$I->seeResponseContainsJson(
			$this->createErrorJsonResponse('validation_error', 'id', 'this_id_is_already_used')
		);
	}

	/**
	 * @param ApiTester $I
	 */
	public function registerWithIncorrectEmail(ApiTester $I)
	{
		$this->userData['payload']['email'] = 'IncorrectEmailAddress';

		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
		$I->seeResponseContainsJson(
			$this->createErrorJsonResponse('validation_error', 'email', 'must_be_correct_email')
		);
	}

	/**
	 * @param ApiTester $I
	 */
	public function registerWithoutEmail(ApiTester $I)
	{
		unset($this->userData['payload']['email']);

		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
		$I->seeResponseContainsJson(
			$this->createErrorJsonResponse('validation_error', 'email', 'is_required')
		);
	}

	/**
	 * @param ApiTester $I
	 * @throws \Exception
	 */
	public function registerWithAlreadyUsedEmail(ApiTester $I)
	{
		UserFixture::load(
			$I,
			$this->userData['payload']['user_id'],
			$this->userData['payload']['email'],
			$this->userData['payload']['password'],
			$this->userData['payload']['first_name'],
			$this->userData['payload']['last_name']
		);

		$this->userData['payload']['user_id'] = Uuid::uuid4()->toString();
		$I->sendPOST(self::REGISTER_PATH, $this->userData);
		$I->seeResponseCodeIs(Response::HTTP_BAD_REQUEST);
		$I->seeResponseContainsJson(
			$this->createErrorJsonResponse('validation_error', 'email', 'this_email_is_already_used')
		);
	}

	/**
	 * @param string $type
	 * @param string $property
	 * @param string $message
	 * @return array
	 */
	private function createErrorJsonResponse(string $type, string $property, string $message): array
	{
		return [
			'error' => [
				'type' => $type,
				'property' => $property,
				'message' => $message
			]
		];
	}
}