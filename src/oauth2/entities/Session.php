<?php

namespace oauth2\entities;

use entities\User;
use entities\AbstractEntity;
use entities\Entity;

/**
 * Сущность, которая предствляет сессию пользователя в REST-сервере
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Session extends AbstractEntity implements Entity
{
    /**
     * @var int
     */
    private $id;

    /**
     * Строковой идентификатор сессии
     *
     * @var string
     */
    private $hash;

    /**
     * @var \DateTime
     */
    private $createdAt;

    /**
     * @var User
     */
    private $user;

    /**
     * Набор разрешений, доступных пользователю
     *
     * @var Scope[]
     */
    private $scopes = [];

    /**
     * @inheritdoc
     */
    public function validate()
    {
        if (!$this->hash) {
            $this->errors[] = 'Hash is empty or missing';
        }

        if (!$this->createdAt) {
            $this->errors[] = 'Date of creation must be specified';
        }

        if (!$this->user) {
            $this->errors[] = 'User is empty or missing';
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
    public function getHash()
    {
        return $this->hash;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return Scope[]
     */
    public function getScopes()
    {
        return $this->scopes;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @param string $hash
     */
    public function setHash($hash)
    {
        $this->hash = $hash;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        $this->user = $user;
    }

    /**
     * @param Scope[] $scopes
     */
    public function setScopes(array $scopes)
    {
        $this->scopes = $scopes;
    }

    /**
     * @param array $map
     */
    public function load(array $map)
    {
        if (isset($map['id'])) {
            $this->setId((int)$map['id']);
        }

        if (isset($map['hash'])) {
            $this->setHash($map['hash']);
        }

        if (isset($map['createdAt'])) {
            $this->setCreatedAt(new \DateTime($map['createdAt']));
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
                'hash' => $this->getHash(),
                'userId' => $this->getUser()->getId(),
            ];
        }

        return [];
    }
}