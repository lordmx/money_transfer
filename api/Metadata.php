<?php

namespace api;

class Metadata
{
	/**
	 * @var int
	 */
	private $limit;

	/**
	 * @var int
	 */
	private $offset;

	/**
	 * @var int
	 */
	private $count;

	/**
	 * @var array
	 */
	private $data = [];

	/**
	 * @param int $limit
	 * @param int $offset
	 * @param int $count
	 */
	public function __construct($limit = 10, $offset = 0, $count = 0)
	{
		$this->limit = $limit;
		$this->offset = $offset;
		$this->sort = $sort;
	}

	/**
	 * @return int
	 */
	public function getLimit()
	{
		return $this->limit;
	}

	/**
	 * @return int
	 */
	public function getOffset()
	{
		return $this->offset;
	}

	/**
	 * @return int
	 */
	public function getCount()
	{
		return $this->count;
	}

	/**
	 * @return array
	 */
	public function getData()
	{
		return $this->data;
	}

	/**
	 * @param array $data
	 */
	public function setData(array $data)
	{
		$this->data = $data;
	}

	/**
	 * @return array
	 */
	public function toMap()
	{
		$result = [
			'limit' => (int)$this->getLimit(),
			'offset' => (int)$this->getOffset(),
			'count' => (int)$this->getCount()
		];

		$data = $this->getData();

		if ($data) {
			$result['data'] = $data;
		}

		return $result;
	}
}