<?php
declare(strict_types=1);

namespace App\Security\EventListener;

use App\Security\JWTUserInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Event\AuthenticationSuccessEvent;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AuthenticationSuccessListener
 * @package App\Security\EventListener
 */
class AuthenticationSuccessListener
{
	/**
	 * @var RequestStack
	 */
	private $requestStack;

	/**
	 * AuthenticationSuccessListener constructor.
	 * @param RequestStack $requestStack
	 */
	public function __construct(RequestStack $requestStack)
	{
		$this->requestStack = $requestStack;
	}

	/**
	 * @param AuthenticationSuccessEvent $event
	 */
	public function onAuthenticationSuccessResponse(AuthenticationSuccessEvent $event)
	{
		$user = $event->getUser();
		if (!$user instanceof JWTUserInterface) {
			throw new \InvalidArgumentException('internal_authorization_error');
		}

		$data = $event->getData();
		$data['data'] = array(
			'user_id' => $user->getUserId()->toString()
		);

		$event->setData($data);
	}
}