<?php
declare(strict_types=1);

namespace App\Util\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ErrorResponseFactory
 * @package App\Util\Response
 */
class ErrorResponseFactory
{
	/**
	 * @param \Exception $e
	 * @return ValidationErrorResponse|JsonResponse
	 */
	public static function createResponse(\Exception $e): JsonResponse
	{
		switch (true) {
			case $e instanceof \InvalidArgumentException:
				return new ValidationErrorResponse($e->getMessage());
				break;
			case $e->getPrevious() instanceof \InvalidArgumentException:
				return new ValidationErrorResponse($e->getPrevious()->getMessage());
				break;
			default:
				return new JsonResponse(['error' => ['type' => 'unknown_error']], JsonResponse::HTTP_NOT_ACCEPTABLE);
		}
	}
}