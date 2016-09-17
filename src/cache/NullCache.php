<?php

namespace cache;

class NullCache implements CacheInterface
{
	/**
	 * @inheritdoc
	 */
	public function get($key)
	{
		return false;
	}

	/**
	 * @inheritdoc
	 */
	public function set($key, $value)
	{

	}
}