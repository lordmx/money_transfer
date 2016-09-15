<?php

namespace entities;

class Wallet extends BaseEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $currencyId;

    /**
     * @inheritdoc
     */
    public function validate()
    {
        if (!$this->currencyId) {
            $this->errors[] = 'Currencies ISO code is empty or missing'
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
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getCurrencyId()
    {
        return $this->currencyId;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * @param string $currencyId
     */
    public function setCurrencyId($currencyId)
    {
        $this->currencyId = $currencyId;
    }

    /**
     * @inheritdoc
     */
    public function load(array $map)
    {
        if (isset($map['id'])) {
            $this->setId((int)$map['id']);
        }

        if (isset($map['title'])) {
            $this->setTitle($map['title']);
        }

        if (isset($map['currencyId'])) {
            $this->setCurrencyId($map['currencyId']);
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
                'title' => $this->getTitle(),
                'currencyId' => $this->getCurrencyId(),
            ];
        }

        return [];
    }
}