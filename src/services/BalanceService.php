<?php

namespace services;

use repositories\TransactionRepository;
use entities\User;
use entities\Wallet;

/**
 * Сервис для получения баланса пользователя
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
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
     * Получить баланс пользователя по кошельку
     *
     * @param User $user
     * @param Wallet $wallet
     */
    public function getBalanceFor(User $user, Wallet $wallet)
    {
        return $this->transactionRepository->getBalanceFor($user, $wallet);
    }
}