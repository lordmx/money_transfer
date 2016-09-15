<?php

namespace repositories\oauth2;

use gateways\GatewayInterface;
use entities\oauth2\Session;
use repositories\AbstractRepository;
use repositories\RepositoryInterface;
use repositories\UserRepository;
use repositories\exceptions\IntegrityException;

class DocumentRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param UserRepository $userRepository
	 */
	public function __construct(GatewayInterface $gateway, UserRepository $userRepository)
	{
		parent::__construct($gateway);

		$this->userRepository = $userRepository;
	}

	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Session();
	}

	/**
	 * @inheritdoc
	 * @throws IntegrityException
	 */
	protected function populateEntity(array $map)
	{
		$entity = parent::populateEntity($map);

		if (isset($map['userId'])) {
			$user = $this->userRepository->findById((int)$map['userId']);

			if (!$user) {
				throw new IntegrityException('Wrong user given');
			}

			$this->setUser($user);
		} else {
			throw new IntegrityException('User is empty or missing');
		}
	}
}