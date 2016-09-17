<?php

namespace oauth2\http;

use \Symfony\Component\HttpFoundation\Request;

class FoundationAdapterRequest implements RequestInterface
{
	/**
	 * @var Request
	 */
	private $request;

	/**
	 * @param Request $request
	 */
	public function __construct(Request $request)
	{
		$this->request = $request;
	}

	/**
	 * @inheritdoc
	 */
	public function getParam($key)
	{
		return $this->request->request->get($key);
	}

	/**
	 * @inheritdoc
	 */
	public function getHeader($name)
	{
		return $this->request->headers->get($name);
	}
}