<?php

namespace di;

class Container
{
	/**
	 * @var array
	 */
	private $services = [];

	/**
	 * @var \Closure[]
	 */
	private $lazy = [];

	/**
	 * @param string $name
	 * @param mixed $service
	 */
	public function register($name, $service)
	{
		$this->services[$name] = $service;
	}

	/**
	 * @param string $name
	 * @param \Closure $callback
	 */
	public function lazy($name, \Closure $callback)
	{
		$this->lazy[$name] = $callback;
	}

	/**
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