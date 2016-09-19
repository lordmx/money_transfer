<?php

namespace entities;

/**
 * Сущность для хранения информации о движении средств конкреного пользователя на конкретном кошельке. Может быть использована для
 * получения баланса пользователя по указанному кошельку - как сумма всех движений за все время (или определенный отрезок). Для отмены
 * последствий, например, перевода средств от одного пользователя к другому необходимо удалить соответствующую пару операций (списания
 * средств с кошелька первого пользователя и зачисления другому).
 *
 * Операция движения средств создаются посредствам соответствующий документов и связаны с ними: при откате документа эти операции будут
 * удалены, а при выполнении созданы.
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Transaction extends AbstractEntity implements Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * Пользователь, на кошельке которого произошло движение
     *
     * @var User
     */
    private $user;

    /**
     * Дата операции
     *
     * @var \DateTime
     */
    private $createdAt;

    /**
     * Кошелек, по которому произошло движение
     *
     * @var Wallet
     */
    private $wallet;

    /**
     * Сумма движения
     *
     * @var float
     */
    private $amount;

    /**
     * Документ, который инициировал создании операции. Один документ может быть инициатором множества операций: пара операций при переводе от
     * пользователя к пользователю, множества операций при массовом выводе средств и т.п.
     *
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
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
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
                'userId' => $this->getUser()->getId(),
            ];
        }

        return [];
    }
}