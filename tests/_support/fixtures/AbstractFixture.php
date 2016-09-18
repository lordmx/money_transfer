<?php

namespace tests\_support\fixtures;

abstract class AbstractFixture
{
	/**
	 * @var \Doctrine\DBAL\Connection 
	 */
	protected $conn;

	/**
	 * @var string
	 */
	protected $table;

	/**
	 * @var string
	 */
	protected $dataPath;

	/**
	 * @var string[]
	 */
	protected $depends = [];

	/**
	 * @param \Doctrine\DBAL\Connection $conn
	 */
	public function __construct(\Doctrine\DBAL\Connection $conn)
	{
		$this->conn = $conn;
	}

	/**
	 * @return string
	 */
	public function getTable()
	{
		return $this->table;
	}

	/**
	 * @return string
	 */
	public function getDataPath()
	{
		return $this->dataPath;
	}

	/**
	 * @return string[]
	 */
	public function getDepends()
	{
		return $this->depends;
	}

	public function run()
	{
		$this->insertData();

		foreach ($this->getDepends() as $depend) {
			$depend = new $depend($this->conn);
			$depend->run();
		}
	}

	public static function load()
	{
		$db = getDb();
		$fixture = new static($db);
		$fixture->run();
	}

	protected function truncateTable()
	{
		$this->conn->executeQuery('TRUNCATE TABLE ' . $this->getTable());
	}

	/**
	 * @return array
	 */
	protected function getData()
	{
		if (file_exists($this->dataPath)) {
			return require $this->dataPath;
		}

		return [];
	}

	/**
	 * @return bool
	 */
	protected function insertData()
	{

		$this->truncateTable();
		$data = $this->getData();

		if (!$data) {
			return false;
		}		

		foreach ($data as $row) {
			$keys = array_keys($row);
			$sql = 'INSERT INTO ' . $this->getTable() . '(' . implode(',', $keys) . ') VALUES(';

			foreach ($row as $key => $value) {
				if (is_numeric($value)) {
					$value = $this->conn->quote($value, \PDO::PARAM_INT);
				} elseif (is_bool($value)) {
					$value = $this->conn->quote($value, \PDO::PARAM_BOOL);
				} else {
					$value = $this->conn->quote($value);
				}

				$row[$key] = $value;
			}

			$sql .= implode(',', $row) . ')';

			$this->conn->executeQuery($sql);
		}
	}
}