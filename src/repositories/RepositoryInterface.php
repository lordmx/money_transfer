<?php

namespace repositories;

use entities\Entity;
use dto\DtoInterface;

/**
 * Интерфейс репозитория сущностей. Репозиторий служит для создания объект из данных и предствления этих данных для
 * шлюза таблицы БД
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface RepositoryInterface
{
	/**
	 * Получить сущность по ее идентификатору
	 *
	 * @param int $id
	 * @return Entity|null
	 */
	public function findById($id);

	/**
	 * Получить все сущности (выборка ограничена по количеству сущностей и смещению сначала выборки)
	 *
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
	public function findAll($limit = 10, $offset = 0);

	/**
	 * Найти сущности по критериям, предствленным в виде DTO (выборка ограничена по количеству сущностей 
	 * и смещению сначала выборки)
	 *
	 * @param DtoInterface $dto
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
	public function findByDto(DtoInterface $dto, $limit = 10, $offset = 0);

	/**
	 * Получить полное количество сущностей
	 *
	 * @return int
	 */
	public function countAll();

	/**
	 * Получить количество сущностей, попадающих под критерии, предствленные в виде DTO
	 *
	 * @param DtoInterface $dto
	 * @return int
	 */
	public function countByDto(DtoInterface $dto);

	/**
	 * Создать сущность с использованием данных, полученных из DTO
	 *
	 * @param DtoInterface $dto
	 * @return Entity
	 */
	public function create(DtoInterface $dto);

	/**
	 * Сохранить сущность (создать новую или обновить)
	 *
	 * @param Entity $entity
	 * @return Entity
	 */
	public function save(Entity $entity);

	/**
	 * Удалить сущность
	 *
	 * @param Entity $entity
	 * @return Entity
	 */
	public function delete(Entity $entity);
}