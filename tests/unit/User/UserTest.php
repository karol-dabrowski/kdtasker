<?php
declare(strict_types=1);

namespace App\Tests\Unit\User;

use Faker\Factory;
use Tasker\Model\User\Domain\User;
use Tasker\Model\User\Domain\UserEmail;
use Tasker\Model\User\Domain\UserId;
use Tasker\Model\User\Domain\UserName;
use Tasker\Model\User\Domain\UserPassword;

/**
 * Class UserTest
 * @package App\Tests\Unit\User
 */
class UserTest extends \Codeception\Test\Unit
{
	/**
	 * @var array
	 */
	private $userData = array();

	protected function _before()
	{
		$faker = Factory::create();

		$this->userData['id'] = UserId::generate();
		$this->userData['email'] = UserEmail::fromString($faker->email);
		$this->userData['name'] = UserName::fromString($faker->firstName, $faker->lastName);
		$this->userData['password'] = UserPassword::fromString("TestPassword123");
		$this->userData['confirmed'] = false;
	}

	public function testUserCanBeCreated()
	{
		$user = User::create(
			$this->userData['id'],
			$this->userData['email'],
			$this->userData['name'],
			$this->userData['password'],
			$this->userData['confirmed']
		);

		$this->assertInstanceOf(User::class, $user);
		$this->assertInstanceOf(UserId::class, $user->id());
		$this->assertSame($this->userData['id'], $user->id());
		$this->assertInstanceOf(UserEmail::class, $user->email());
		$this->assertSame($this->userData['email'], $user->email());
		$this->assertInstanceOf(UserPassword::class, $user->password());
		$this->assertSame($this->userData['password'], $user->password());
	}

	public function testUserDoesNotThrowAnExceptionWhenSettingCreatedAndModifiedDate()
	{
		$user = User::create(
			$this->userData['id'],
			$this->userData['email'],
			$this->userData['name'],
			$this->userData['password'],
			$this->userData['confirmed']
		);

		$user->setCreatedDateTime(new \DateTimeImmutable());
		$this->assertTrue(true);

		$user->setModifiedDateTime(new \DateTime());
		$this->assertTrue(true);
	}
}