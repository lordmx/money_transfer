<?php

namespace repositories;

use gateways\GatewayInterface;
use entities\Transaction;
use entities\User;
use entities\Wallet;
use entities\Document;
use dto\HistoryDto;

class TransactionRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var WalletRepository
	 */
	private $walletRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param UserRepository $userRepository
	 * @param WalletRepository $paymentRuleRepository
	 */
	public function __construct(GatewayInterface $gateway, UserRepository $userRepository, WalletRepository $walletRepository)
	{
		parent::__construct($gateway);

		$this->userRepository = $userRepository;
		$this->walletRepository = $walletRepository;
	}

	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Transaction();
	}

	/**
	 * @param HistoryDto $dto
	 * @return Transaction[]
	 */
	public function findHistory(HistoryDto $dto)
	{
		$criteria = $this->getHistory($criteria);
		$rows = $this->gateway->findByCriteria($criteria);
		$result = [];

		foreach ($rows as $row) {
			$result[] = $this->populateEntity($row);
		}

		return $result;
	}

	/**
	 * @param HistoryDto $dto
	 * @return int
	 */
	public function getHistoryCount(HistoryDto $dto)
	{
		$criteria = $this->getHistory($criteria);
		return $this->gateway->countByCriteria($criteria);
	}

	/**
	 * @param Document $document
	 * @return Transaction[]
	 */
	public function findByDocument(Document $document)
	{
		$criteria = ['documentId' => $document->getId()];
		$rows = $this->gateway->findByCriteria($criteria);
		$result = [];

		foreach ($rows as $row) {
			$result[] = $this->populateEntity($row);
		}

		return $result;
	}

	/**
	 * @param User $user
	 * @param Wallet $wallet
	 * @return float
	 */
	public function getBalanceFor(User $user, Wallet $wallet)
	{
		return $this->gateway->getBalanceFor($user->getId(), $wallet->getId());
	}

	/**
	 * @inheritdoc
	 * @throws IntegrityException
	 */
	protected function populateEntity(array $map)
	{
		$entity = parent::populateEntity($map);

		if (isset($map['userId'])) {
			$this->setUser($this->getUser($map['userId']));
		} else {
			throw new IntegrityException('User is empty or missing');
		}

		if (isset($map['walletId'])) {
			$this->setWallet($this->getWallet($map['walletId']));
		} else {
			throw new IntegrityException('Wallet is empty or missing');
		}
	}

	/**
	 * @param HistoryDto $dto
	 * @return array
	 */
	private function getHistoryCriteria(HistoryDto $dto)
	{
		$criteria = [
			'userId' => $dto->getUserId(),
		];

		if ($dto->getWalletId()) {
			$criteria['walletId'] = $dto->getWalletId();
		}

		if ($dto->getDateFrom()) {
			$criteria['createdAt >= ?'] = $dto->getDateFrom()->format(DATE_W3C);
		}

		if ($dto->getDateTo()) {
			$criteria['createdAt <= ?'] = $dto->getDateTo()->format(DATE_W3C);
		}

		return $criteria;
	}

	/**
	 * @param int $userId
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

	/**
	 * @param int $walletId
	 * @return Wallet
	 * @throws IntegrityException
	 */
	private function getWallet($walletId)
	{
		$wallet = $this->walletRepository->findById((int)$walletId);

		if (!$wallet) {
			throw new IntegrityException('Wrong wallet given');
		}

		return $wallet;
	}
}