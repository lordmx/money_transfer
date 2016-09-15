<?php

namespace repositories;

use entities\Entity;
use dto\Dto;

interface RepositoryInterface
{
	/**
	 * @param int $id
	 * @return Entity|null
	 */
	public function findById($id);

	/**
	 * @param Dto $dto
	 * @return Entity[]
	 */
	public function findByDto(Dto $dto);

	/**
	 * @param Dto $dto
	 * @return Entity
	 */
	public function create(Dto $dto);

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