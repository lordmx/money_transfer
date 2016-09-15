<?php

namespace entities;

class Transaction extends BaseEntity
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var User
	 */
	private $user;

	/**
	 * @var \DateTime
	 */
	private $createdAt;

	/**
	 * @var PaymentRule
	 */
	private $paymentRule;

	/**
	 * @var Wallet
	 */
	private $wallet;

	/**
	 * @var float
	 */
	private $amount;

	/**
	 * @var Document
	 */
	private $document;

	/**
	 * @inheritdoc
	 */
	public function validate()
	{
		if (!$this->user) {
			$this->errors[] = 'User is empty or missing';
		}

		if (!$this->createdAt) {
			$this->errors[] = 'Date of creation must be specified';
		}

		if (!$this->paymentRule) {
			$this->errors[] = 'Payment rule is empty or missing';
		}

		if (!$this->wallet) {
			$this->errors[] = 'Wallet is empty or missing';
		}

		if ($this->amount == 0.0) {
			$this->errors[] = 'Amount must be not equals null';
		}

		if (!$this->document) {
			$this->errors[] = 'Document is empty or missing';
		}

		return empty($this->errors);
	}

	/**
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return User
	 */
	public function getUser()
	{
		return $this->user;
	}

	/**
	 * @return \DateTime
	 */
	public function getCreatedAt()
	{
		return $this->createdAt;
	}

	/**
	 * @return PaymentRule
	 */
	public function getPaymentRule()
	{
		return $this->paymentRule;
	}

	/**
	 * @return Wallet
	 */
	public function getWallet()
	{
		return $this->wallet;
	}

	/**
	 * @return float
	 */
	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * @return Document
	 */
	public function getDocument()
	{
		return $this->document;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param \DateTime $createdAt;
	 */
	public function setCreatedAt(\DateTime $createdAt)
	{
		$this->createdAt = $createdAt;
	}

	/**
	 * @param PaymentRule $paymentRule
	 */
	public function setPaymentRule(PaymentRule $paymentRule)
	{
		$this->paymentRule = $paymentRule;
	}

	/**
	 * @param Wallet $wallet
	 */
	public function setWallet(Wallet $wallet)
	{
		$this->wallet = $wallet;
	}

	/**
	 * @param float $amount
	 */
	public function setAmount($amount)
	{
		$this->amount = $amount;
	}

	/**
	 * @param Document $document
	 */
	public function setDocument(Document $document)
	{
		$this->document = $document;
	}

	/**
	 * @inheritdoc
	 */
	public function load(array $map)
	{
		if (isset($map['id'])) {
			$this->setId((int)$map['id']);
		}

		if (isset($map['createdAt'])) {
			$this->setCreatedAt(new \DateTime($map['createdAt']));
		}

		if (isset($map['amount'])) {
			$this->setAmount((float)$map['amount']);
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
				'createdAt' => $this->getCreatedAt()->format(DATE_W3C),
				'amount' => $this->getAmount(),
				'walletId' => $this->getWallet()->getId(),
				'documentId' => $this->getDocument()->getId(),
				'paymentRuleId' => $this->getPaymentRule()->getId(),
			];
		}

		return [];
	}
}