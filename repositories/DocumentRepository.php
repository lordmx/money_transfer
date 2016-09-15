<?php

namespace repositories;

use gateways\GatewayInterface;
use entities\Document;
use entities\User;
use entities\types\DocumentType;

class DocumentRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @var DocumentType[]
	 */
	private $types = [];

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param UserRepository $userRepository
	 * @param DocumentType[] $types
	 */
	public function __construct(GatewayInterface $gateway, UserRepository $userRepository, array $types = [])
	{
		parent::__construct($gateway);

		$this->userRepository = $userRepository;
		$this->types = $types;
	}

	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Document();
	}

	/**
	 * @inheritdoc
	 * @throws IntegrityException
	 */
	protected function populateEntity(array $map)
	{
		$entity = parent::populateEntity($map);

		if (isset($map['creatorId'])) {
			$entity->setCreator($this->getUser($map['creatorId']));
		} else {
			throw new IntegrityException('Creator is empty or missing');
		}

		if (isset($map['executorId'])) {
			$entity->setExecutor($this->getUser($map['executorId']));
		} else {
			throw new IntegrityException('Executor is empty or missing');
		}

		if (isset($map['type'])) {
			foreach ($this->types as $type) {
				if ($type->getName() == $map['type']) {
					$entity->setType($type);
				}
			}

			if (!$entity->getType()) {
				throw new IntegrityException('Wrong document type given');
			}
		} else {
			throw new IntegrityException('Document type is empty or missing');
		}
	}

	/**
	 * @param int $id
	 * @return User
	 * @throws IntegrityException
	 */
	private function getUser($userId)
	{
		$user = $this->userRepository->findById((int)$userId);

		if (!$user) {
			throw new IntegrityException('Wrong user given');
		}

		return $user;
	}
}