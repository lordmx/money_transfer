<?php

namespace oauth2\grants;

use oauth2\entities\Session;

interface GrantInterface
{
	/**
	 * @param string $token
	 * @return Session
	 */
	public function createSession($token);
}