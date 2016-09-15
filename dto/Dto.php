<?php

namespace $dto;

interface Dto
{
	/**
	 * @return array
	 */
	public function toMap();

	/**
	 * @param array $map
	 * @return Dto
	 */
	public static function fromMap(array $map);
}