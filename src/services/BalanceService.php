<?php

namespace services;

use repositories\TransactionRepository;
use entities\User;
use entities\Wallet;

class BalanceService
{
	/**
	 * @var TransactionRepository
	 */
	private $transactionRepository;

	/**
	 * @param TransactionRepository $transactionRepository
	 */
	public function __construct(TransactionRepository $transactionRepository)
	{
		$this->transactionRepository = $transactionRepository;
	}

	/**
	 * @param User $user
	 * @param Wallet $wallet
	 */
	public function getBalanceFor(User $user, Wallet $wallet)
	{
		return $this->transactionRepository->getBalanceFor($user, $wallet);
	}
}