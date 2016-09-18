<?php

namespace oauth2\repositories;

use gateways\GatewayInterface;
use oauth2\gateways\UserScopeGateway;
use oauth2\entities\Session;
use entities\User;
use repositories\AbstractRepository;
use repositories\RepositoryInterface;
use repositories\UserRepository;
use oauth2\repositories\ScopeRepository;
use repositories\exceptions\IntegrityException;

/**
 * Репозиторий сущностей для сущности сессии пользователя API (сущность Session)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class SessionRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var UserScopeGateway
	 */
	private $userScopeGateway;

	/**
	 * @var ScopeRepository
	 */
	private $scopeRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param UserRepository $userRepository
	 */
	public function __construct(
		GatewayInterface $gateway,
		UserScopeGateway $userScopeGateway,
		ScopeRepository $scopeRepository,
		UserRepository $userRepository
	) {
		parent::__construct($gateway);

		$this->userRepository = $userRepository;
		$this->userScopeGateway = $userScopeGateway;
		$this->scopeRepository = $scopeRepository;
	}

	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Session();
	}

	/**
	 * Получить действующую сессию пользовтеля
	 *
	 * @param User $user
	 * @return Session|null
	 */
	public function findByUser(User $user)
	{
		$criteria = ['userId' => $user->getId()];
		$rows = $this->gateway->findByCriteria($criteria);

		if (!$rows) {
			return null;
		}

		return $this->populateEntity($rows[0]);
	}

	/**
	 * Создать новую сессию для пользователя
	 *
	 * @param User $user
	 * @return Session
	 */
	public function createForUser(User $user)
	{
		$session = new Session();
		$session->setUser($user);
		$session->setCreatedAt(new \DateTime());
		$session->setHash(md5(uniqid()));
		$this->save($session);

		$this->associateScopes($session);

		return $session;
	}

	/**
	 * @param Session $session
	 */
	protected function associateScopes(Session $session)
	{
		$scopeIds = $this->userScopeGateway->findUserScopeIds($session->getUser()->getId());
		$scopes = [];

		foreach ($scopeIds as $scopeId) {
			$scope = $this->scopeRepository->findById($scopeId);

			if (!$scope) {
				continue;
			}

			$scopes[] = $scope;
		}

		$session->setScopes($scopes);
		$session->getUser()->setScopes($scopes);
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

			$entity->setUser($user);
		} else {
			throw new IntegrityException('User is empty or missing');
		}

		$this->associateScopes($entity);

		return $entity;
	}
}