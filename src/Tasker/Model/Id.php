<?php
declare(strict_types=1);

namespace Tasker\Model;

/**
 * Interface Id
 * @package Tasker\Model
 */
interface Id
{
	/**
	 * @return Id
	 */
	public static function generate(): Id;

	/**
	 * @param string $id
	 * @return Id
	 */
	public static function fromString(string $id): Id;

	/**
	 * @return string
	 */
	public function toString(): string;

	/**
	 * @param Id $otherId
	 * @return bool
	 */
	public function isEqual(Id $otherId): bool;
}