<?php

namespace gateways;

use entities\entity;

interface Gateway
{
	/**
	 * @param int $id
	 * @return array
	 */
	public function findById($id);

	/**
	 * @param array $criteria
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function findByCriteria(array $criteria, $limit = 10, $offset = 0);

	/**
	 * @param array $data
	 * @return int
	 */
	public function insert(array $data);

	/**
	 * @param int $id
	 * @param array $data
	 */
	public function update($id, array $data);

	/**
	 * @param int $id
	 */
	public function delete($id);
}