<?php

namespace oauth2\http;

interface RequestInterface
{
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getParam(string $key);

	/**
	 * @param string $name
	 * @return string
	 */
	public function getHeader(string $name);
}