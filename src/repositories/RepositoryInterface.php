<?php

namespace repositories;

use entities\Entity;
use dto\DtoInterface;

interface RepositoryInterface
{
	/**
	 * @param int $id
	 * @return Entity|null
	 */
	public function findById($id);

	/**
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
	public function findAll($limit = 10, $offset = 0);

	/**
	 * @param DtoInterface $dto
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
	public function findByDto(DtoInterface $dto, $limit = 10, $offset = 0);

	/**
	 * @return int
	 */
	public function countAll();

	/**
	 * @param DtoInterface $dto
	 * @return int
	 */
	public function countByDto(DtoInterface $dto);

	/**
	 * @param DtoInterface $dto
	 * @return Entity
	 */
	public function create(DtoInterface $dto);

	/**
	 * @param Entity $entity
	 * @return Entity
	 */
	public function save(Entity $entity);

	/**
	 * @param Entity $entity
	 * @return Entity
	 */
	public function delete(Entity $entity);
}