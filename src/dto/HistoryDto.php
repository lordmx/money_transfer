<?php

namespace dto;

use dto\exceptions\ValidationException;

/**
 * Объект запроса для API-метода получения истории движения средств пользователя 
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class HistoryDto implements DtoInterface
{
	/**
	 * @var int
	 */
	private $walletId;

	/**
	 * @var int
	 */
	private $userId;

	/**
	 * @var \DateTime|null
	 */
	private $dateFrom;

	/**
	 * @var \DateTime|null
	 */
	private $dateTo;

	/**
	 * @return int
	 */
	public function getWalletId()
	{
		return $this->walletId;
	}

	/**
	 * @return int
	 */
	public function getUserId()
	{
		return $this->userId;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getDateFrom()
	{
		return $this->dateFrom;
	}

	/**
	 * @return \DateTime|null
	 */
	public function getDateTo()
	{
		return $this->dateTo;
	}

	/**
	 * @return int
	 */
	public function getTargetUserId()
	{
		return $this->targetUserId;
	}

	/**
	 * @param int $walletId
	 */
	public function setWalletId($walletId)
	{
		$this->walletId = $walletId;
	}

	/**
	 * @param int $userId
	 */
	public function setUserId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * @param int $userId
	 */
	public function setTargetWalletId($userId)
	{
		$this->userId = $userId;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDateFrom(\DateTime $date)
	{
		$this->dateFrom = $date;
	}

	/**
	 * @param \DateTime $date
	 */
	public function setDateTo(\DateTime $date)
	{
		$this->dateTo = $date;
	}

	/**
	 * @return array
	 */
	public function toMap()
	{
		return [
			'wallet_id' => (int)$this->getWalletId(),
			'user_id' => (int)$this->getUserId(),
			'date_from' => $this->getDateFrom() ? $this->getDateFrom()->format(DATE_W3C) : null,
			'date_to' => $this->getDateTo() ? $this->getDateTo()->format(DATE_W3C) : null,
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

		if (!empty($map['wallet_id'])) {
			$dto->setWalletId((int)$map['wallet_id']);
		}

		if (!empty($map['user_id'])) {
			$dto->setUserId((int)$map['user_id']);
		} else {
			throw new ValidationException('User is empty or missing');
		}

		if (!empty($map['date_from'])) {
			$dto->setDateFrom($map['date_from']);
		}

		if (!empty($map['date_to'])) {
			$dto->setDateTo($map['date_to']);
		}

		return $dto;
	}
}