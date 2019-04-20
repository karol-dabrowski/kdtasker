<?php
declare(strict_types=1);

namespace App\Util\Response;

use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class ValidationErrorResponse
 * @package App\Util\Response
 */
class ValidationErrorResponse extends JsonResponse
{
	/**
	 * ValidationErrorResponse constructor.
	 * @param string $exceptionMessage
	 */
	public function __construct(string $exceptionMessage)
	{
		$messageArray = explode('|', $exceptionMessage);
		$property = $messageArray[0];
		$message = $messageArray[1];

		$data = [
			'error' => [
				'type' => 'validation_error',
				'property' => $property,
				'message' => $message
			]
		];

		parent::__construct($data, JsonResponse::HTTP_BAD_REQUEST);
	}

}
