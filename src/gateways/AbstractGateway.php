<?php

namespace gateways;

use helpers\HelperString;

/**
 * Абстрактный шлюз таблицы БД, реализует базовую логику получения данных, вставки, удаления и изменения рядов. 
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
abstract class AbstractGateway
{
    /**
     * Ресурс соединения с БД
     *
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
     * Получить запись по ее идентификатору
     *
     * @param int $id
     * @return array
     */
    public function findById($id)
    {
        $pk = $this->getPrimaryKey();
        $row = $this->conn->fetchAssoc('SELECT * FROM ' . $this->getTable() . ' WHERE ' . $pk . ' = ?', [$id]);

        if (!$row) {
            return $row;
        }

        return $this->unmap($row);
    }

    /**
     * Получить ряды по набору критериев, переданных в виде ассоциативного массива, ограничить выборку с учетом количества рядов
     * и смещения сначала выборки
     *
     * @param array $criteria
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findByCriteria(array $criteria, $limit = 10, $offset = 0)
    {
        $result = [];
        $sql = 'SELECT * FROM ' . $this->getTable();

        if ($criteria) {
            $sql .= $this->getSqlFromCriteria($criteria);
        }

        if ($limit <= 0) {
            $limit = 10;
        }

        $sql .= ' LIMIT ' . (int)$offset . ', ' . (int)$limit;
        $rows = $this->conn->fetchAll($sql);

        foreach ($rows as $row) {
            $result[] = $this->unmap($row);
        }

        return $result;
    }

    /**
     * Получить количество записей, попадающих под набор критериев, переданных в виде ассоциативного массива
     *
     * @param array $criteria
     * @return int
     */
    public function countByCriteria(array $criteria)
    {
        $sql = 'SELECT COUNT(' . $this->getPrimaryKey() . ') FROM ' . $this->getTable();

        if ($criteria) {
            $sql .= $this->getSqlFromCriteria($criteria);
        }

        $row = $this->conn->fetchArray($sql);

        if (!$row) {
            return 0;
        }

        return reset($row);
    }

    /**
     * Вставить новую запись
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $this->conn->insert($this->getTable(), $this->map($data));
        return $this->conn->lastInsertId();
    }

    /**
     * Обновить данные записи по ее идентификатору
     *
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data)
    {
        $criteria = [$this->getPrimaryKey() => $id];
        $this->conn->update($this->getTable(), $this->map($data), $criteria);
    }

    /**
     * Удалить запись по ее идентификатору
     *
     * @param int $id
     */
    public function delete($id)
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
     * @param array $data
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
     * @param array $data
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
     * @param array $criteria
     * @return string
     */
    private function getSqlFromCriteria(array $criteria)
    {
        $sql = ' WHERE ';
        $fields = array_keys($criteria);

        for ($i = 0, $count = count($fields); $i < $count; $i++) {
            $value = $criteria[$fields[$i]];
            $field = $this->normalizeField($fields[$i]);
            $operand = '= ?';

            if (strpos($field, '?') !== false) {
                $operand  = '';
            }

            if (is_array($value)) {
                foreach ($value as $j => $subValue) {
                    $value[$j] = $this->quote($subValue);
                }

                $value = implode(',', $value);
                $operand = 'IN(?)';

            } else {
                $value = $this->quote($value);
            }

            $where = $field . ($operand ? ' ' . $operand : '');
            $where = str_replace('?', $value, $where);

            $sql .= $where;

            if ($i < $count - 1) {
                $sql .= ' AND ';
            }
        }

        return $sql;
    }

    /**
     * @param string $field
     * @return string
     */
    private function normalizeField($field)
    {
        $field = trim($field);

        if (strpos($field, '?') === false) {
            return HelperString::toUnderscore($field);
        }

        if (strpos($field, ' ') !== false) {
            list($field, $operand) = explode(' ', $field, 2);
            $field = trim($field);
            $operand = trim($operand);

            return HelperString::toUnderscore($field) . ' ' . $operand;
        }

        $field = substr($field, 0, -1);
        $field = trim($field);

        if (in_array($field[count($field) - 2], ['>', '<', '='])) {
            $operand = substr($field, count($field) - 2);
            $field = trim(substr($field, -2));

            return HelperString::toUnderscore($field) . ' ' . $operand . ' ?';
        }

        $operand = substr($field, count($field) - 1);
        $field = trim(substr($field, -1));

        return HelperString::toUnderscore($field) . ' ' . $operand . ' ?';
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
    abstract protected function getTable();
}