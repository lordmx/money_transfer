<?php

use api\handlers;

use entities\User;
use api\Metadata;
use \Symfony\Component\HttpFoundation\Request;

interfance HandlerInterface
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
	 * @param User $user
	 * @param Request $request
	 * @return \Closure
	 */
	public function getCallback(User $user, Request $request);
}