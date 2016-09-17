<?php

namespace api\handlers;

use di\Container;
use entities\User;

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
	 * @inheritdoc
	 */
	public function setUser(User $user)
	{
		$this->user = $user;
	}
}