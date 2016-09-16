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
	private $sourceUserId;

	/**
	 * @var int
	 */
	private $targetUserId;

	/**
	 * @var string
	 */
	private $notice;

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
	public function getSourceUserId()
	{
		return $this->sourceUserId;
	}

	/**
	 * @return int
	 */
	public function getTargetUserId()
	{
		return $this->targetUserId;
	}

	/**
	 * @return string
	 */
	public function getNotice()
	{
		return $this->notice;
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
	public function setSourceUserId($userId)
	{
		$this->sourceUserId = $userId;
	}

	/**
	 * @param int $userId
	 */
	public function setTargetUserId($userId)
	{
		$this->targetUserId = $userId;
	}

	/**
	 * @param string $notice
	 */
	public function setNotice($notice)
	{
		$this->notice = $notice;
	}

	/**
	 * @return array
	 */
	public function toMap()
	{
		return [
			'source_wallet_id' => $this->getSourceWalletId(),
			'target_wallet_id' => $this->getTargetWalletId(),
			'source_user_id' => $this->getSourceUserId(),
			'target_user_id' => $this->getTargetUserId(),
			'amount' => $this->getAmount(),
			'notice' => $this->getNotice(),
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

		if (!empty($map['source_user_id'])) {
			$dto->setSourceUserId((int)$map['source_user_id']);
		} else {
			throw new ValidationException('Source user ID is empty or missing');
		}

		if (!empty($map['target_user_id'])) {
			$dto->setTargetUserId((int)$map['target_user_id']);
		} else {
			throw new ValidationException('Target user ID is empty or missing');
		}

		if (!empty($map['notice'])) {
			$dto->setNotice($map['notice']);
		}

		return $dto;
	}
}