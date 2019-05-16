<?php
declare(strict_types=1);

namespace App\Security\EventListener;

use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationFailureEvent;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AuthenticationFailureListener
 * @package App\Security\EventListener
 */
class AuthenticationFailureListener
{
	/**
	 * @param AuthenticationFailureEvent $event
	 */
	public function onAuthenticationFailureResponse(AuthenticationFailureEvent $event)
	{
		$data = [
			'error' => [
				'type' => 'authentication_error',
				'message' => 'login_or_password_is_incorrect'
			]
		];

		$response = new JsonResponse($data,JsonResponse::HTTP_UNAUTHORIZED, ['WWW-Authenticate' => 'Bearer']);
		$event->setResponse($response);
	}
}