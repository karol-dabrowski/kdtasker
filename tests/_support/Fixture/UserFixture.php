<?php
declare(strict_types=1);

namespace App\Tests\Fixture;

use App\Tests\ApiTester;

/**
 * Class UserFixture
 * @package App\Tests\Fixture
 */
class UserFixture
{
	/**
	 * @param ApiTester $I
	 * @param string $id
	 * @param string $email
	 * @param string $password
	 * @param string $firstName
	 * @param string $lastName
	 * @param bool $confirmed
	 * @param \DateTime|null $created
	 * @param \DateTime|null $modified
	 * @throws \Exception
	 */
	public static function load(
		ApiTester $I,
		string $id,
		string $email,
		string $password,
		string $firstName,
		string $lastName,
		bool $confirmed = false,
		\DateTime $created = null,
		\DateTime $modified = null
	)
	{
		$userData = [
			'id' => $id,
			'email' => $email,
			'password' => password_hash($password, PASSWORD_DEFAULT),
			'first_name' => $firstName,
			'last_name' => $lastName,
			'confirmed' => $confirmed,
			'created' => is_null($created) ?
				(new \DateTime())->format('Y-m-d H:i:s') : $created->format('Y-m-d H:i:s'),
			'modified' => is_null($modified) ?
				(new \DateTime())->format('Y-m-d H:i:s') : $modified->format('Y-m-d H:i:s')
		];

		$I->haveInDatabase('users', $userData);
	}
}
