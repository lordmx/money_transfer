<?php

namespace entities;

class Exchange extends BaseEntity
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $sourceCurrencyId;

	/**
	 * @var string
	 */
	private $targetCurrencyId;

	/**
	 * @var float
	 */
	private $incomingRate;

	/**
	 * @var float
	 */
	private $outcomingRate;

	/**
	 * @inheritdoc
	 */
	public function validate()
	{
		if (!$this->sourceCurrencyId) {
			$this->errors[] = 'Source currency ISO code is missing or empty';
		}

		if (!$this->targetCurrencyId) {
			$this->errors[] = 'Target currency ISO code is missing or empty';
		}

		if (!is_null($this->incomingRate) && $this->incomingRate <= 0) {
			$this->errors[] = 'Incoming rate must be greater than null';
		}

		if (!is_null($this->outcomingRate) && $this->outcomingRate <= 0) {
			$this->errors[] = 'Outcoming rate must be greater than null';
		}
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getSourceCurrencyId()
	{
		return $this->sourceCurrencyId;
	}

	/**
	 * @return string
	 */
	public function getTargetCurrencyId()
	{
		return $this->targetCurrencyId;
	}

	/**
	 * @return float
	 */
	public function getIncomingRate()
	{
		return $this->incomingRate;
	}

	/**
	 * @return float
	 */
	public function getOutcomingRate()
	{
		return $this->outcomingRate;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $currencyId
	 */
	public function setSourceCurrencyId($currencyId)
	{
		$this->sourceCurrencyId = $currencyId;
	}

	/**
	 * @param string $currencyId
	 */
	public function setTargetCurrencyId($currencyId)
	{
		$this->targetCurrencyId = $currencyId;
	}

	/**
	 * @param float $rate
	 */
	public function setOutcomingRate($rate)
	{
		$this->outcomingRate = $rate;
	}

	/**
	 * @param float $rate
	 */
	public function setIncomingRate($rate)
	{
		$this->incomingRate = $rate;
	}

	/**
	 * @inheritdoc
	 */
	public function load(array $map)
	{
		if (isset($map['id'])) {
			$this->setId((int)$map['id']);
		}

		if (isset($map['sourceCurrencyId'])) {
			$this->setSourceCurrencyId($map['sourceCurrencyId']);
		}

		if (isset($map['targetCurrencyId'])) {
			$this->setTargetCurrencyId($map['targetCurrencyId']);
		}

		if (isset($map['incomingRate'])) {
			$this->setIncomingRate($map['incomingRate']);
		}

		if (isset($map['outcomingRate'])) {
			$this->setOutcomingRate($map['outcomingRate']);
		}
	}

	/**
	 * @inheritdoc
	 */
	public function toMap()
	{
		if ($this->validate()) {
			return [
				'id' => $this->getId(),
				'sourceCurrencyId' => $this->getSourceCurrencyId(),
				'targetCurrencyId' => $this->getTargetCurrencyId(),
				'incomingRate' => $this->getIncomingRate(),
				'outcomingRate' => $this->getOutcomingRate(),
			];
		}

		return [];
	}
}