<?php

namespace gateways;

use entities\entity;

/**
 * Интерфейс шлюза таблицы БД
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface GatewayInterface
{
    /**
     * Найти запись по ее идентификатору
     *
     * @param int $id
     * @return array
     */
    public function findById($id);

    /**
     * Найти записи по набору критериев
     *
     * @param array $criteria
     * @param int $limit
     * @param int $offset
     * @return array
     */
    public function findByCriteria(array $criteria, $limit = 10, $offset = 0);

    /**
     * Получить количество записей, попадающих под набор критериев
     *
     * @param array $criteria
     * @return int
     */
    public function countByCriteria(array $criteria);

    /**
     * Вставить запись
     *
     * @param array $data
     * @return int
     */
    public function insert(array $data);

    /**
     * Обновить данные записи по ее идентификатору
     *
     * @param int $id
     * @param array $data
     */
    public function update($id, array $data);

    /**
     * Удалить запись по ее идентификатору
     *
     * @param int $id
     */
    public function delete($id);
}