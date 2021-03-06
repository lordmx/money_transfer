<?php

namespace repositories;

use gateways\GatewayInterface;
use entities\PaymentRule;
use repositories\exceptions\IntegrityException;
use entities\Wallet;

/**
 * Репозиторий сущностей для сущности платежных правил (сущность PaymentRule)
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class PaymentRuleRepository extends AbstractRepository implements RepositoryInterface
{
    /**
     * @var WalletRepository
     */
    private $walletRepository;

    /**
     * @param GatewayInterface $gateway
     * @param WalletRepository $walletRepository
     */
    public function __construct(GatewayInterface $gateway, WalletRepository $walletRepository)
    {
        parent::__construct($gateway);

        $this->walletRepository = $walletRepository;
    }

    /**
     * @inheritdoc
     */
    protected function createEntity()
    {
        return new PaymentRule();
    }

    /**
     * Получить платежное правило для пары кошельков
     *
     * @param int $sourceWalletId
     * @param int $targetWalletId
     * @return PaymentRule|null
     */
    public function findByWalletIds($sourceWalletId, $targetWalletId)
    {
        $criteria = [
            'sourceWalletId' => (int)$sourceWalletId,
            'targetWalletId' => (int)$targetWalletId,
        ];

        $rows = $this->gateway->findByCriteria($criteria);

        if (!$rows) {
            return null;
        }

        return $this->populateEntity($rows[0]);
    }

    /**
     * @inheritdoc
     * @throws IntegrityException
     */
    protected function populateEntity(array $map)
    {
        $entity = parent::populateEntity($map);

        if (isset($map['sourceWalletId'])) {
            $entity->setSourceWallet($this->getWallet($map['sourceWalletId']));
        } else {
            throw new IntegrityException('Source wallet is empty or missing');
        }

        if (isset($map['targetWalletId'])) {
            $entity->setTargetWallet($this->getWallet($map['targetWalletId']));
        } else {
            throw new IntegrityException('Target wallet is empty or missing');
        }

        return $entity;
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