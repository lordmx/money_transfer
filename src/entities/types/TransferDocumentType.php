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

/**
 * Тип документа перевода средств от пользователя к пользователю. Суть операции: создания одной операции на списания средств для исходного
 * кошелька и сходного пользователя и операции зачисления для целевого пользователя и кошелька, при условии, что на исходном кошельке 
 * достаточно средвств для совершения операции.
 * 
 * Перевод может быть как в рамках одного кошелька так и в рамках разных кошельков (включая мультивалютные переводы).
 * Для проверки корректности операции используются платежные правила. Для получения суммы перевода для мультивалютных операций 
 * используется сервис получения курсов валют.  
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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

		// пытаем найти платежное правило для пары кошельков
		$paymentRule = $this->paymentRuleRepository->findByWalletIds(
			$dto->getSourceWalletId(),
			$dto->getTargetWalletId()
		);

		if (!$paymentRule) {
			$document->markAsError($user, 'No any rule for transfer money from wallet to wallet');
			return $document;
		}

		$amount = $dto->getAmount();

		if ($paymentRule->getMaxAmount() && $amount >= $paymentRule->getMaxAmount()) {
			$document->markAsError($user, 'Amount must be lesser than ' . $paymentRule->getMaxAmount());
			return $document;
		}

		if ($paymentRule->getMinAmount() && $amount <= $paymentRule->getMinAmount()) {
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

		if ($sourceUser->getId() == $targetUser->getId()) {
			$document->markAsError($user, 'Source user and target user should not be equals');
			return $document;
		}

		$balance = $this->balanceService->getBalanceFor($sourceUser, $paymentRule->getSourceWallet());

		if ($amount > $balance) {
			$document->markAsError($user, 'Insufficient funds');
			return $document;
		}

		$totalAmount = $amount;

		// если платежным правилом определена комиссия, то считаем ее сверху суммы списания
		if ($paymentRule->getCommission()) {
			$totalAmount *= (1 + $paymentRule->getCommission() / 100);
		}

		$targetAmount = $amount;

		// если платежным правилом определен кросс-курс для операции, то используем его для получения суммы зачисления,
		// иначе получаем сумму через сервис валютных курсов (который для не мультивалютных операций вернет исходную сумму,
		// а для мультивалютных вычислит сумму с использованием заданного курса)
		if ($paymentRule->getCrossRate()) {
			$targetAmount *= $paymentRule->getCrossRate();
		} else {
			$targetAmount = $this->exchangeService->calc(
				$paymentRule->getSourceWallet()->getCurrencyId(),
				$paymentRule->getTargetWallet()->getCurrencyId(),
				$amount
			);
		}

		// создаем движения для исходного пользователя и кошелька
		$sourceTransaction = new Transaction();
		$sourceTransaction->setDocument($document);
		$sourceTransaction->setWallet($paymentRule->getSourceWallet());
		$sourceTransaction->setUser($sourceUser);
		$sourceTransaction->setCreatedAt(new \DateTime());
		$sourceTransaction->setAmount(-$totalAmount);
		$this->transactionRepository->save($sourceTransaction);

		// создаем движения для целевого пользователя и кошелька
		$targetTransaction = new Transaction();
		$targetTransaction->setDocument($document);
		$targetTransaction->setWallet($paymentRule->getTargetWallet());
		$targetTransaction->setUser($targetUser);
		$targetTransaction->setCreatedAt(new \DateTime());
		$targetTransaction->setAmount($targetAmount);
		$this->transactionRepository->save($targetTransaction);

		// помечаем документ как исполненный
		$document->markAsCompleted($user);
	}

	/**
	 * @inheritdoc
	 */
	public function backward(Document $document)
	{
		// Получаем движения по кошелькам, связанные с данным документом и удаляем их
		foreach ($this->transactionRepository->findByDocument($document) as $transaction) {
			$this->transactionRepository->delete($transaction);
		}

		// помечаем документ доступ для последующего выполнения
		$document->markAsCreated();

		return $document;
	}
}