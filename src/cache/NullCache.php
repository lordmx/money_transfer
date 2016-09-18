<?php

namespace cache;

/**
 * Null-кэшер, может быть использован как заглушка для временного отключения кэширования.
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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