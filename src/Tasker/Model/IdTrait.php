<?php
declare(strict_types=1);

namespace Tasker\Model;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

/**
 * Trait IdTrait
 * @package Tasker\Model
 */
trait IdTrait
{
	/**
	 * @var UuidInterface
	 */
	private $uuid;

	/**
	 * IdTrait constructor.
	 * @param UuidInterface $uuid
	 */
	private function __construct(UuidInterface $uuid)
	{
		$this->uuid = $uuid;
	}

	/**
	 * @return Id
	 * @throws \Exception
	 */
	public static function generate(): Id
	{
		return new self(Uuid::uuid4());
	}

	/**
	 * @param string $idString
	 * @return Id
	 */
	public static function fromString(string $idString): Id
	{
		return new self(Uuid::fromString($idString));
	}

	/**
	 * @return string
	 */
	public function toString(): string
	{
		return $this->uuid->toString();
	}

	/**
	 * @param Id $other
	 * @return bool
	 */
	public function isEqual(Id $other): bool
	{
		return \get_class($this) === \get_class($other) && $this->uuid->equals($other->uuid);
	}
}