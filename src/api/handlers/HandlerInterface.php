<?php

namespace api\handlers;

use api\Metadata;
use entities\User;
use \Symfony\Component\HttpFoundation\Request;

interface HandlerInterface
{
	/**
	 * @return string
	 */
	public function getRoute();

	/**
	 * @return string
	 */
	public function getMethod();

	/**
	 * @return string[]
	 */
	public function getScopes();

	/**
	 * @param Request $request
	 * @return \Closure
	 */
	public function getCallback(Request $request);

	/**
	 * @param User $user
	 */
	public function setUser(User $user);
}