<?php

namespace repositories;

use gateways\GatewayInterface;
use entities\Transaction;
use entities\User;
use entities\PaymentRule;

class TransactionRepository extends AbstractRepository implements RepositoryInterface
{
	/**
	 * @var UserRepository
	 */
	private $userRepository;

	/**
	 * @var PaymentRuleRepository
	 */
	private $paymentRuleRepository;

	/**
	 * @param GatewayInterface $gateway
	 * @param UserRepository $userRepository
	 * @param PaymentRuleRepository $paymentRuleRepository
	 */
	public function __construct(GatewayInterface $gateway, UserRepository $userRepository, PaymentRuleRepository $paymentRuleRepository)
	{
		parent::__construct($gateway);

		$this->userRepository = $userRepository;
		$this->paymentRuleRepository = $paymentRuleRepository;
	}

	/**
	 * @inheritdoc
	 */
	protected function createEntity()
	{
		return new Transaction();
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

		if (isset($map['paymentRuleId'])) {
			$this->setPaymentRule($this->getPaymentRule($map['paymentRuleId']));
		} else {
			throw new IntegrityException('Payment rule is empty or missing');
		}
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
	 * @param int $paymentRuleId
	 * @return PaymentRule
	 * @throws IntegrityException
	 */
	private function getPaymentRule($paymentRuleId)
	{
		$paymentRule = $this->paymentRuleRepository->findById((int)$paymentRuleId);

		if (!$paymentRule) {
			throw new IntegrityException('Wrong payment rule given');
		}

		return $paymentRule;
	}
}