<?php

namespace oauth2;

use oauth2\grants\GrantInterface;
use oauth2\http\RequestInterface;
use oauth2\entities\Session;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class Server
{
	const GRANT_BEARER = 'bearer';

	/**
	 * @var GrantInterface
	 */
	private $grants = [];

	/**
	 * @param string $name
	 * @param GrantInterface $grant
	 */
	public function registerGrant($name, GrantInterface $grant)
	{
		$this->grants[$name] = $grant;
	}

	/**
	 * @param RequestInterface $request
	 * @return Session
	 * @throws UnauthorizedHttpException
	 */
	public function createSession(RequestInterface $request)
	{
		$grantName = $this->extractGrantName($request->getHeader('Authorization'));
		$token = $this->extractToken($request->getHeader('Authorization'));

		if (!isset($this->grants[$grantName])) {
			throw new UnauthorizedHttpException('Wrong grant given');
		}

		return $this->grants[$grantName]->createSession($token);
	}

	/**
	 * @param string $header
	 * @return string
	 */
	protected function extractGrantName($header)
	{
		list($grantName,) = explode(' ', $header, 2);
		return strtolower($grantName);
	}

	/**
	 * @param string $header
	 * @return string
	 */
	protected function extractToken($header)
	{
		list(, $token) = explode(' ', $header, 2);
		return $token;
	}
}