<?php
declare(strict_types = 1);

namespace App\Controller;

use App\Util\QueryFactory;
use App\Util\Response\ErrorResponseFactory;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Prooph\Common\Messaging\MessageFactory;
use Prooph\ServiceBus\CommandBus;
use Prooph\ServiceBus\QueryBus;
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
	 * @var QueryBus
	 */
	private $queryBus;

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

	private const COMMAND_NAME_ATTRIBUTE = 'command';
	private const QUERY_NAME_ATTRIBUTE = 'query';

	/**
	 * ApiController constructor.
	 * @param CommandBus $commandBus
	 * @param MessageFactory $messageFactory
	 * @param TokenStorageInterface $tokenStorageInterface
	 * @param JWTTokenManagerInterface $jwtManager
	 */
	public function __construct(
		CommandBus $commandBus,
		QueryBus $queryBus,
		MessageFactory $messageFactory,
		TokenStorageInterface $tokenStorageInterface,
		JWTTokenManagerInterface $jwtManager
	) {
		$this->commandBus = $commandBus;
		$this->queryBus = $queryBus;
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
		$data = json_decode($request->getContent(), true);
		$commandName = $request->attributes->get(self::COMMAND_NAME_ATTRIBUTE);
		$payload = ['payload' => $data['payload']];
		$payload['payload']['user_id'] = $this->getRequesterID();

		try {
			$command = $this->messageFactory->createMessageFromArray($commandName, $payload);
			$this->commandBus->dispatch($command);
		} catch(\Exception $exception) {
			return ErrorResponseFactory::createResponse($exception);
		}

		return new JsonResponse();
	}

	/**
	 * @param Request $request
	 * @return JsonResponse
	 */
	public function getAction(Request $request)
	{
		$attributes = $request->attributes->all();
		$queryName = $request->attributes->get(self::QUERY_NAME_ATTRIBUTE);
		$query = QueryFactory::createQuery($queryName, $attributes);

		$response = $this->queryBus->dispatch($query);
		$finalResponse = null;
		$response->then(
			function($value) use (&$finalResponse) {
				$finalResponse = $value;
			}
		);

		return new JsonResponse($finalResponse);
	}

	/**
	 * @return string
	 */
	private function getRequesterID(): string
	{
		$decodedJwtToken = $this->jwtManager->decode($this->tokenStorageInterface->getToken());
		if(!isset($decodedJwtToken['user_id'])) {
			throw new \RuntimeException('user_id|is_missing', JsonResponse::HTTP_NOT_ACCEPTABLE);
		}

		return $decodedJwtToken['user_id'];
	}
}