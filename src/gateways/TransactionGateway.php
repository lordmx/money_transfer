<?php

namespace gateways;

/**
 * Шлюз для таблицы движения средств по кошелькам (transactions)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class TransactionGateway extends AbstractGateway implements GatewayInterface
{
    /**
     * @inheritdoc
     */
    protected function getTable()
    {
        return 'transactions';
    }

    /**
     * Получить баланс (как сумма движения пользователя по кошельку)
     *
     * @param int $userId
     * @param int $walletId
     * @return float
     */
    public function getBalanceFor($userId, $walletId)
    {
        $row = $this->conn->fetchArray(
            'SELECT SUM(amount) FROM ' . $this->getTable() . ' WHERE user_id = ' . $userId . ' AND wallet_id = ' . $walletId
        );

        if (!$row) {
            return 0.0;
        }

        return (float)reset($row);
    }
}