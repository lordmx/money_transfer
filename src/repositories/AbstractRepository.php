<?php

namespace repositories;

use entities\Entity;
use dto\DtoInterface;
use gateways\GatewayInterface;
use repositories\exceptions;
use cache\CacheInterface;

abstract class AbstractRepository
{
	/**
	 * @var GatewayInterface
	 */
	protected $gateway;

	/**
	 * @var CacheInterface
	 */
	protected $cacher;

	/**
	 * @param GatewayInterface $gateway
	 */
	public function __construct(GatewayInterface $gateway)
	{
		$this->gateway = $gateway;
	}

	/**
	 * @param int $id
	 * @return Entity|null
	 */
	public function findById($id)
	{
		$map = $this->gateway->findById($id);

		if (!$map) {
			return null;
		}

		return $this->populateEntity($map);
	}

	/**
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
	public function findAll($limit = 10, $offset = 0)
	{
		$items = $this->gateway->findByCriteria([], $limit, $offset);

		foreach ($items as $map) {
			$result[] = $this->populateEntity($map);
		}

		return $result;
	}

	/**
	 * @param DtoInterface $dto
	 * @param int $limit
	 * @param int $offset
	 * @return Entity[]
	 */
	public function findByDto(DtoInterface $dto, $limit = 10, $offset = 0)
	{
		$criteria = $dto->toMap();

		if ($limit <= 0) {
			$limit = 10;
		}

		foreach ($criteria as $key => $value) {
			if (is_null($value)) {
				unset($criteria[$key]);
			}
		}

		$result = [];
		$items = $this->gateway->findByCriteria($criteria, $limit, $offset);

		foreach ($items as $map) {
			$result[] = $this->populateEntity($map);
		}

		return $result;
	}

	/**
	 * @return int
	 */
	public function countAll()
	{
		return $this->gateway->countByCriteria([]);
	}

	/**
	 * @param DtoInterface $dto
	 * @return int
	 */
	public function countByDto(DtoInterface $dto)
	{
		$criteria = $dto->toMap();

		foreach ($criteria as $key => $value) {
			if (is_null($value)) {
				unset($criteria[$key]);
			}
		}

		return $this->gateway->countByCriteria($criteria);
	}

	/**
	 * @param DtoInterface $dto
	 * @return Entity
	 */
	public function create(DtoInterface $dto)
	{
		$map = $dto->toMap();
		$entity = $this->createEntity();
		$entity->load($map);

		return $entity;
	}

	/**
	 * @param Entity $entity
	 * @return Entity
	 * @throws ValidationException
	 */
	public function save(Entity $entity)
	{
		if (!$entity->validate()) {
			$errors = $entity->getErrors();
			throw new ValidationException(reset($errors));
		}

		if ($entity->getId() > 0) {
			$dirty = $entity->getDirty();

			if ($dirty) {
				$this->gateway->update($entity->getId(), $dirty);
			}
		} else {
			$this->gateway->insert($entity->toMap());
		}

		return $entity;
	}

	/**
	 * @param Entity $entity
	 */
	public function delete(Entity $entity)
	{
		$this->gateway->delete($entity->getId());
		return $entity;
	}

	/**
	 * @return CacheInterface|null
	 */
	public function getCacher()
	{
		return $this->cacher;
	}

	/**
	 * @param CacheInterface $cacher
	 */
	public function setCacher(CacheInterface $cacher)
	{
		$this->cacher = $cacher;
	}

	/**
	 * @param array $map
	 * @return Entity
	 */
	protected function populateEntity(array $map)
	{
		$entity = $this->createEntity();
		$entity->load($map);

		return $entity;
	}

	abstract protected function createEntity();
}