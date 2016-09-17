<?php

namespace api\handlers;

use di\Container;

abstract class AbstractHandler
{
	/**
	 * @var Container
	 */
	protected $di;

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
		return api\Server::METHOD_GET;
	}

	/**
	 * @inheritdoc
	 */
	public function getScopes()
	{
		return [];
	}
}