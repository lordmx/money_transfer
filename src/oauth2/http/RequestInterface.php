<?php

namespace oauth2\http;

interface RequestInterface
{
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function getParam($key);

	/**
	 * @param string $name
	 * @return string
	 */
	public function getHeader($name);
}