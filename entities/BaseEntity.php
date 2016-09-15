<?php

namespace entities;

class BaseEntity implements Entity
{
	protected $dirty = [];

	protected $errors = [];

	/**
	 * @return array
	 */
	public function getErrors()
	{
		return $this->errors;
	}

	/**
	 * @return $array
	 */
	public function getDirty()
	{
		return $this->dirty;
	}
}