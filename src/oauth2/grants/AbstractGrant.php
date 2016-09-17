<?php

namespace oauth2\grants;

use oauth2\repositories\SessionRepository;
use repositories\UserRepository;
use oauth2\http\RequestInterface;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

abstract class AbstractGrant
{
	/**
	 * @var SessionRepository
	 */
	private $sessionRepository;

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @param SessionRepository $sessionRepository
	 * @param UserRepository $userRepository
	 */
	public function __construct(SessionRepository $sessionRepository, UserRepository $userRepository)
	{
		$this->userRepository = $userRepository;
		$this->sessionRepository = $sessionRepository;
	}

	/**
	 * @throws UnauthorizedHttpException
	 */
	protected function throwUnauthorizedHttpException()
	{
		return new UnauthorizedHttpException('Authorization required');
	}
}