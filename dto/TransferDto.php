<?php

namespace dto;

use dto\exceptions\ValidationException;

class TransferDto implements DtoInterface
{
	/**
	 * @var int
	 */
	private $sourceWalletId;

	/**
	 * @var int
	 */
	private $targetWalletId;

	/**
	 * @var float
	 */
	private $amount;

	/**
	 * @var int
	 */
	private $userId;

	/**
	 * @return int
	 */
	public function getSourceWalletId()
	{
		return $this->sourceWalletId;
	}

	/**
	 * @return int
	 */
	public function getTargetWalletId()
	{
		return $this->targetWalletId;
	}

	/**
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * @return int
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * @param int $walletId
	 */
	public function setSourceWalletId($walletId)
	{
		$this->sourceWalletId = $walletId;
	}

	/**
	 * @param int $walletId
	 */
	public function setTargetWalletId($walletId)
	{
		$this->targetWalletId = $walletId;
	}

	/**
	 * @param float $amount
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	/**
	 * @param int $userId
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * @return array
	 */
	public function toMap()
	{
		return [
			'source_wallet_id' => $this->getSourceWalletId(),
			'target_wallet_id' => $this->getTargetWalletId(),
			'amount' => $this->getAmount(),
			'user_id' => $this->getUserId(),
		];
	}

	/**
	 * @param array $map
	 * @return TransferDto
	 * @throws ValidationException
	 */
	public static function fromMap(array $map)
	{
		$dto = new self();

		if (!empty($map['source_wallet_id'])) {
			$dto->setSourceWalletId((int)$map['source_wallet_id']);
		} else {
			throw new ValidationException('Source wallet is empty or missing');
		}

		if (!empty($map['target_wallet_id'])) {
			$dto->setTargetWalletId((int)$map['target_wallet_id']);
		} else {
			throw new ValidationException('Target wallet is empty or missing');
		}

		if (!empty($map['amount']) && $map['amount'] > 0) {
			$dto->setAmount((float)$map['amount']);
		} else {
			throw new ValidationException('Amount must be greater than null');
		}

		if (!empty($map['user_id'])) {
			$dto->setUserId((int)$map['user_id']);
		} else {
			throw new ValidationException('User ID is empty or missing');
		}

		return $dto;
	}
}