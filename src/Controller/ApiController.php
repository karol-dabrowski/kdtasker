<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Util\Response\ErrorResponseFactory;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class ApiController
 * @package App\Controller
 */
final class ApiController
{
	/**
	 * @var CommandBus
	 */
	private $commandBus;

	/**
	 * @var MessageFactory
	 */
	private $messageFactory;

	/**
	 * @var TokenStorageInterface
	 */
	private $tokenStorageInterface;

	/**
	 * @var JWTTokenManagerInterface
	 */
	private $jwtManager;

	private const NAME_ATTRIBUTE = 'command';

	/**
	 * ApiController constructor.
	 * @param CommandBus $commandBus
	 * @param MessageFactory $messageFactory
	 * @param TokenStorageInterface $tokenStorageInterface
	 * @param JWTTokenManagerInterface $jwtManager
	 */
	public function __construct(
		CommandBus $commandBus,
		MessageFactory $messageFactory,
		TokenStorageInterface $tokenStorageInterface,
		JWTTokenManagerInterface $jwtManager
	) {
		$this->commandBus = $commandBus;
		$this->messageFactory = $messageFactory;
		$this->tokenStorageInterface = $tokenStorageInterface;
		$this->jwtManager = $jwtManager;
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function postAction(Request $request)
	{
		$decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
		$userId = $decodedJwtToken['user_id'];

		$data = json_decode($request->getContent(), true);
		$commandName = $request->attributes->get(self::NAME_ATTRIBUTE);
		$payload = ['payload' => $data['payload']];
		$payload['payload']['user_id'] = $userId;

		try {
			$command = $this->messageFactory->createMessageFromArray($commandName, $payload);
			$this->commandBus->dispatch($command);
		} catch(\Exception $exception) {
			return ErrorResponseFactory::createResponse($exception);
		}

		return new JsonResponse();
	}
}