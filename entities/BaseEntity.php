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
	 * @param string $field
	 */
	public function addDirty($field)
	{
		$this->dirty[] = $field;
	}

	/**
	 * @return $array
	 */
	public function getDirty()
	{
		return $this->dirty;
	}
}