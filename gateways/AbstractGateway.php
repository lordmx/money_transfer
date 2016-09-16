<?php

namespace gateways;

use helpers\HelperString;

abstract class AbstractGateway
{
	/**
	 * @var \Doctrine\DBAL\Connection 
	 */
	protected $conn;

	/**
	 * @param \Doctrine\DBAL\Connection $conn
	 */
	public function __construct(\Doctrine\DBAL\Connection $conn)
	{
		$this->conn = $conn;
	}

	/**
	 * @param int $id
	 * @return array
	 */
	public function findById($id)
	{
		$pk = $this->getPrimaryKey();
		$row = $this->conn->fetchArray('SELECT * FROM ' . $this->getTable() . ' WHERE ' . $pk . ' = ?', [$id]);

		if (!$row) {
			return $row;
		}

		return $this->unmap($row);
	}

	/**
	 * @param array $criteria
	 * @param int $limit
	 * @param int $offset
	 * @return array
	 */
	public function findByCriteria(array $criteria, $limit = 10, $offset = 0)
	{
		$result = [];
		$sql = 'SELECT * FROM ' . $this->getTabel();

		if ($criteria) {
			$sql .= ' WHERE ';
			$fields = array_keys($criteria);

			for ($i = 0, $count = count($fields); $i < $count; $i++) {
				$field = $fields[$i];
				$value = $criteria[$field];
				$operand = '= ?';

				if (is_array($value)) {
					foreach ($value as $j => $subValue) {
						$value[$j] = $this->quote($subValue);
					}

					$value = implode(',', $value);
					$operand = 'IN(?)';

				} else {
					$value = $this->quote($value);
				}

				$where = $field . ' ' . $operand;
				$where = str_replace('?', $value, $operand);

				$sql .= $where;

				if ($i < $count - 2) {
					$sql .= ' AND ';
				}
			}
		}

		if ($limit <= 0) {
			$limit = 10;
		}

		$sql .= ' LIMIT ' . (int)$limit . ',' . (int)$offset;
		
		$rows = $this->conn->fetchAll($sql);

		foreach ($rows as $row) {
			$result[] = $this->unmap($row);
		}

		return $result;
	}

	/**
	 * @param array $data
	 * @return int
	 */
	public function insert(array $data)
	{
		$this->conn->insert($this->getTable(), $this->map($data));
		return $this->conn->lastInsertId();
	}

	/**
	 * @param int $id
	 * @param array $data
	 */
	public function update($id, array $data)
	{
		$criteria = [$this->getPrimaryKey() => $id];
		$this->conn->update($this->getTable(), $data, $criteria);
	}

	/**
	 * @param Entity $entity
	 */
	public function delete(Entity $entity)
	{
		$criteria = [$this->getPrimaryKey() => $id];
		$this->conn->delete($this->getTable(), $criteria);
	}

	/**
	 * @param mixed $stmt
	 * @return string
	 */
	protected function quote($stmt)
	{
		if (is_bool($stmt)) {
			$stmt = (int)$stmt;
		}

		if (is_numeric($stmt)) {
			return $this->conn->quote($stmt, \PDO::PARAM_INT);
		}

		return $this->conn->quote($stmt);
	}

	/**
	 * @return array
	 */
	protected function map($data)
	{
		$result = [];

		foreach ($data as $field => $value) {
			$result[HelperString::toUnderscore($field)] = $value;
		}

		return $result;
	}

	/**
	 * @return array
	 */
	protected function unmap($data)
	{
		$result = [];

		foreach ($data as $field => $value) {
			$result[HelperString::toCamelCase($field)] = $value;
		}

		return $result;
	}

	/**
	 * @return string
	 */
	protected function getPrimaryKey()
	{
		return 'id';
	}

	/**
     * @return string
     */
	abstract public function getTable();
}