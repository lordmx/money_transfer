<?php

namespace repositories;

use gateways\GatewayInterface;
use entities\PaymentRule;
use repositories\exceptions\IntegrityException;
use entities\Wallet;

class PaymentRuleRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @var WalletRepository
	 */
	private $walletRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param WalletReposition $walletRepository
	 */
	public function __construct(GatewayInterface $gateway, walletRepository $walletRepository)
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
	 * @inheritdoc
	 * @throws IntegrityException
	 */
	protected function populateEntity(array $map)
	{
		$entity = parent::populateEntity($map);

		if (isset($map['sourceWalletId'])) {
			$this->setSourceWallet($this->getWallet($map['sourceWalletId']));
		} else {
			throw new IntegrityException('Source wallet is empty or missing');
		}

		if (isset($map['targetWalletId'])) {
			$this->setTargetWallet($this->getWallet($map['targetWalletId']));
		} else {
			throw new IntegrityException('Target wallet is empty or missing');
		}
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