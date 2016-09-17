<?php

namespace api;

use entities\EntityInterface;
use helpers\HelperString;

class Result
{
	/**
	 * @var string
	 */
	private $resource;

	/**
	 * @var array
	 */
	private $vector = [];

	/**
	 * @var Metadata
	 */
	private $metadata;

	/**
	 * @param Metadata $metadata
	 * @param string $resource
	 * @param array $vector
	 */
	public function __construct(Metadata $metadata, $resource, array $vector = [])
	{
		$this->resource = $resource;
		$this->metadata = $metadata;
		$this->vector = $vector;
	}

	/**
	 * @return string
	 */
	public function toJson()
	{
		$vector = $this->vector;

		foreach ($vector as $i => $item) {
			if ($item instanceof EntityInterface) {
				$map = $vector->toMap();

				foreach ($map as $key => $value) {
					unset($map[$key]);
					$map[HelperString::toUnderscore($key)] = $value;
				}

				$vector[$i] = $map;
			}
		}

		$raw = [
			'resultset' => [
				'metadata' => $metadata->toMap(),
			],
			$this->resource => $vector,
		];

		return json_encode($raw);
	}
}