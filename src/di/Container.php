<?php

namespace di;

/**
 * Простейший DI-контейнер для хранения и построения зависимостей.
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Container
{
	/**
	 * Зарегистрированные сервисы
	 *
	 * @var array
	 */
	private $services = [];

	/**
	 * Сервисы, которые загружаютс "лениво" (будут построены при первом обращении)
	 *
	 * @var \Closure[]
	 */
	private $lazy = [];

	/**
	 * Зарегистрировать сервис
	 *
	 * @param string $name
	 * @param mixed $service
	 */
	public function register($name, $service)
	{
		$this->services[$name] = $service;
	}

	/**
	 * Определить стратегию "ленивой" загрузки сервиса
	 *
	 * @param string $name
	 * @param \Closure $callback
	 */
	public function lazy($name, \Closure $callback)
	{
		$this->lazy[$name] = $callback;
	}

	/**
	 * Получить сервис и/или загрузить его перед этим (если сервис еще не был загружен)
	 *
	 * @param string $name
	 * @return mixed
	 * @throws Exception
	 */
	public function get($name)
	{
		if (isset($this->services[$name])) {
			return $this->services[$name];
		}

		if (isset($this->lazy[$name])) {
			$service = $this->lazy[$name]();
			$this->register($name, $service);

			return $service;
		}

		throw new Exception('Could not found service ' . $name);
	}
}