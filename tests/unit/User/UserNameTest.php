<?php
declare(strict_types=1);

namespace App\Tests\Unit\User;

use Faker\Factory;
use Faker\Generator;
use Tasker\Model\User\Domain\UserName;

/**
 * Class UserNameTest
 * @package App\Tests\Unit\User
 */
class UserNameTest extends \Codeception\Test\Unit
{
	/**
	 * @var Generator
	 */
	private $faker;

	protected function _before()
	{
		$this->faker = Factory::create();
	}

	public function testUserNameCanBeCreatedFromString()
	{
		$firstNameString = $this->faker->firstName;
		$lastNameString = $this->faker->lastName;
		$userName = UserName::fromString($firstNameString, $lastNameString);

		$this->assertInstanceOf(UserName::class, $userName);
		$this->assertSame($firstNameString . ' ' . $lastNameString, $userName->toString());
	}

	public function testUserNameThrowsAnExceptionWhenCreatingFromInvalidFirstName()
	{
		$invalidFirstNameString = $this->faker->firstName . '0';
		$lastNameString = $this->faker->lastName;

		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('first_name|can_contain_only_letters');
		UserName::fromString($invalidFirstNameString, $lastNameString);
	}

	public function testUserNameThrowsAnExceptionWhenCreatingFromInvalidLastName()
	{
		$firstNameString = $this->faker->firstName;
		$invalidLastNameString = $this->faker->lastName . '0';

		$this->expectException(\InvalidArgumentException::class);
		$this->expectExceptionMessage('last_name|can_contain_only_letters_and_dash');
		UserName::fromString($firstNameString, $invalidLastNameString);
	}
}