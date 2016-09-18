<?php

namespace api\handlers;

use di\Container;
use entities\User;
use \Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Абстрактный обработчик API-метода. Определяем HTTP-глагол метода, его роут и логику. 
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
abstract class AbstractHandler
{
	/**
	 * @var Container
	 */
	protected $di;

	/**
	 * @var User
	 */
	protected $user;

	/**
	 * @param Container $di
	 */
	public function __construct(Container $di)
	{
		$this->di = $di;
	}

	/**
	 * @inheritdoc
	 */
	public function getMethod()
	{
		return \api\Server::METHOD_GET;
	}

	/**
	 * @inheritdoc
	 */
	public function getScopes()
	{
		return [];
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return Container
	 */
	public function getContainer()
	{
		return $this->di;
	}

	/**
	 * @inheritdoc
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}

	/**
	 * Убедить, что у текущего пользователя есть необходимые для доступа к методу oauth2-разрешения. В противном случае
	 * породит исключение.
	 *
	 * @param User $user
	 * @return bool
	 * @throws AccessDeniedHttpException
	 */
	public function ensureUserPermitted(User $user)
	{
		$handlerScopes = $this->getScopes();

		if (!$handlerScopes) {
			return true;
		}

		$userScopes = $user->getScopes();

		if (!$userScopes) {
			throw new AccessDeniedHttpException();
		}

		$userScopeNames = [];

		foreach ($userScopes as $scope) {
			$userScopeNames[] = $scope->getAlias();
		}

		foreach ($handlerScopes as $alias) {
			if (!in_array($alias, $userScopeNames)) {
				throw new AccessDeniedHttpException();
			}
		}

		return true;
	}
}