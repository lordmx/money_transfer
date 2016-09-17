<?php

namespace dto;

interface DtoInterface
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