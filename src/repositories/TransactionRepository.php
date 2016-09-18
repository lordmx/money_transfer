<?php

namespace repositories;

use gateways\GatewayInterface;
use entities\Transaction;
use entities\User;
use entities\Wallet;
use entities\Document;
use dto\HistoryDto;
use repositories\exceptions\IntegrityException;

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
	 * @var DocumentRepository
	 */
	private $documentRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param UserRepository $userRepository
	 * @param WalletRepository $paymentRuleRepository
	 * @param DocumentRepository $documentRepository
	 */
	public function __construct(
		GatewayInterface $gateway,
		UserRepository $userRepository,
		WalletRepository $walletRepository,
		DocumentRepository $documentRepository
	) {
		parent::__construct($gateway);

		$this->userRepository = $userRepository;
		$this->walletRepository = $walletRepository;
		$this->documentRepository = $documentRepository;
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
	 * @param int $limit
	 * @param int $offset
	 * @return Transaction[]
	 */
	public function findHistory(HistoryDto $dto, $limit = 10, $offset = 0)
	{
		$criteria = $this->getHistoryCriteria($dto);
		$rows = $this->gateway->findByCriteria($criteria, $limit, $offset);
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
		$criteria = $this->getHistoryCriteria($dto);
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
			$entity->setUser($this->getUser($map['userId']));
		} else {
			throw new IntegrityException('User is empty or missing');
		}

		if (isset($map['walletId'])) {
			$entity->setWallet($this->getWallet($map['walletId']));
		} else {
			throw new IntegrityException('Wallet is empty or missing');
		}

		if (isset($map['documentId'])) {
			$entity->setDocument($this->getDocument($map['documentId']));
		} else {
			throw new IntegrityException('Document is empty or missing');
		}

		return $entity;
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

	/**
	 * @param int $documentId
	 * @return Document
	 * @throws IntegrityException
	 */
	private function getDocument($documentId)
	{
		$document = $this->documentRepository->findById((int)$documentId);

		if (!$document) {
			throw new IntegrityException('Wrong document given');
		}

		return $document;
	}
}