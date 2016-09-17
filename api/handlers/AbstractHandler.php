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
}