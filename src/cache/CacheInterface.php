<?php

namespace cache;

/**
 * Интерфейс простой системы кэширования. Может быть использована для кэширования, например, результатов работы шлюзов таблиц
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface CacheInterface
{
	/**
	 * @param string $key
	 * @return mixed
	 */
	public function get($key);

	/**
	 * @param string $key
	 * @param mixed $value
	 */
	public function set($key, $value);
}