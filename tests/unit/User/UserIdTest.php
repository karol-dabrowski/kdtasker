<?php
declare(strict_types=1);

namespace App\Tests\Unit\User;

use Ramsey\Uuid\Uuid;
use Tasker\Model\User\Domain\UserId;

/**
 * Class UserIdTest
 * @package App\Tests\Unit\User
 */
class UserIdTest extends \Codeception\Test\Unit
{
	/**
	 * @var \App\Tests\UnitTester
	 */
	protected $tester;

	public function testUserIdCanBeGenerated()
	{
		$userId = UserId::generate();
		$this->assertInstanceOf(UserId::class, $userId);

		$userIdString = $userId->toString();
		$this->assertIsString($userIdString);
		$this->assertSame(36, strlen($userIdString));
	}

	public function testUserIdCanBeCreatedFromString()
	{
		$userIdString = Uuid::uuid4()->toString();

		$userId = UserId::fromString($userIdString);
		$this->assertInstanceOf(UserId::class, $userId);
		$this->assertIsString($userId->toString());
		$this->assertSame($userIdString, $userId->toString());
	}

	public function testUserIdCanBeCompared()
	{
		$baseUserId = UserId::generate();
		$sameuserId = $baseUserId;
		$otherUserId = UserId::generate();

		$this->assertTrue($baseUserId->isEqual($sameuserId));
		$this->assertFalse($baseUserId->isEqual($otherUserId));
	}
}