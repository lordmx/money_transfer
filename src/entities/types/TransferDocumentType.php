<?php

namespace entities\types;

use entities\Document;
use entities\User;
use entities\Transaction;
use repositories\TransactionRepository;
use repositories\PaymentRuleRepository;
use repositories\UserRepository;
use services\BalanceService;
use services\ExchangeService;
use dto\DtoInterface;
use dto\TransferDto;

class TransferDocumentType implements DocumentType
{
	/**
	 * @var TransactionRepository
	 */
	private $transactionRepository;

	/**
	 * @var PaymentRuleRepository
	 */
	private $paymentRuleRepository;

	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var BalanceService
	 */
	private $balanceService;

	/**
	 * @var ExchangeService
	 */
	private $exchangeService;

	/**
	 * @param TransactionRepository $transactionRepository
	 * @param PaymentRuleRepository $paymentRuleRepository
	 * @param UserRepository $userRepository
	 * @param BalanceService $balanceService
	 * @param ExchangeService $exchangeService
	 */
	public function __construct(
		TransactionRepository $transactionRepository,
		PaymentRuleRepository $paymentRuleRepository,
		UserRepository $userRepository,
		BalanceService $balanceService,
		ExchangeService $exchangeService
	) {
		$this->transactionRepository = $transactionRepository;
		$this->paymentRuleRepository = $paymentRuleRepository;
		$this->userRepository = $userRepository;
		$this->balanceService = $balanceService;
		$this->exchangeService = $exchangeService;
	}

	/**
	 * @return string
	 */
	public function getName()
	{
		return Document::TYPE_TRANSFER;
	}

	/**
	 * @inheritdoc
	 */
	public function initContext(Document $document, DtoInterface $dto)
	{
		$document->initContext($dto->toMap());
	}

	/**
	 * @inheritdoc
	 */
	public function forward(Document $document, User $user)
	{
		$context = $document->getContext();

		try {
			$dto = TransferDto::fromMap($context);
		} catch (\Exception $e) {
			$document->markAsError($user, $e->getMessage());
			return $document;
		}

		$paymentRule = $this->paymentRuleRepository->findByWalletIds(
			$dto->getSourceWalletId(),
			$dto->getTargetWalletId()
		);

		if (!$paymentRule) {
			$document->markAsError($user, 'No any rule for transfer money from wallet to wallet');
			return $document;
		}

		$amount = $dto->getAmount();

		if ($amount >= $paymentRule->getMaxAmount()) {
			$document->markAsError($user, 'Amount must be lesser than ' . $paymentRule->getMaxAmount());
			return $document;
		}

		if ($amount <= $paymentRule->getMinAmount()) {
			$document->markAsError($user, 'Amount must be greater than ' . $paymentRule->getMinAmount());
			return $document;
		}

		$sourceUser = $this->userRepository->findById($dto->getSourceUserId());

		if (!$sourceUser) {
			$document->markAsError($user, 'Wrong source user given');
			return $document;
		}

		$targetUser = $this->userRepository->findById($dto->getTargetUserId());

		if (!$targetUser) {
			$document->markAsError($user, 'Wrong target user given');
			return $document;
		}

		$balance = $this->balanceService->getBalanceFor($sourceUser, $paymentRule->getSourceWallet());

		if ($amount > $balance) {
			$document->markAsError($user, 'Insufficient funds';
			return $document;
		}

		$sourceTransaction = new Transaction();
		$sourceTransaction->setDocument($document);
		$sourceTransaction->setWallet($paymentRule->getSourceWallet());
		$sourceTransaction->setUser($sourceUser);
		$sourceTransaction->setCreatedAt(new \DateTime());
		$sourceTransaction->setAmount($amount);
		$this->transactionRepository->save($sourceTransaction);

		$targetTransaction = new Transaction();
		$targetTransaction->setDocument($document);
		$targetTransaction->setWallet($paymentRule->getTargetWallet());
		$targetTransaction->setUser($targetUser);
		$targetTransaction->setCreatedAt(new \DateTime());
		$targetTransaction->setAmount($this->balanceService->calc(
			$paymentRule->getSourceWallet()->getCurrencyId(),
			$paymentRule->getTargetWallet()->getCurrencyId(),
			$amount
		));
		$this->transactionRepository->save($targetTransaction);

		$document->markAsCompleted($user);
	}

	/**
	 * @inheritdoc
	 */
	public function backward(Document $document)
	{
		foreach ($this->transactionRepository->findByDocument($document) as $transaction) {
			$this->transactionRepository->delete($transaction);
		}

		$document->markAsCreated();

		return $document;
	}
}