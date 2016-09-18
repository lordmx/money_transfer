<?php

namespace entities;

use oauth2\entities\Scope;

/**
 * Пользователь системы 
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class User extends AbstractEntity implements Entity
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * Токен доступа пользователя для oauth2 
	 *
	 * @var string
	 */
	private $accessToken;

	/**
	 * Разрешенные пользователю ресурсы oauth2
	 *
	 * @var Scope[]
	 */
	private $scopes = [];

	/**
	 * @inheritdoc
	 */
	public function validate()
	{
		return true;
	}

	/**
	 * @inheritdoc
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @return string
	 */
	public function getAccessToken()
	{
		return $this->accessToken;
	}

	/**
	 * @return Scope[]
	 */
	public function getScopes()
	{
		return $this->scopes;
	}

	/**
	 * @inheritdoc
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $token
	 */
	public function setAccessToken($token)
	{
		$this->accessToken = $token;
	}

	/**
	 * @param Scope[] $scopes
	 */
	public function setScopes(array $scopes)
	{
		$this->scopes = $scopes;
	}

	/**
	 * @inheritdoc
	 */
	public function load(array $map)
	{
		if (isset($map['id'])) {
			$this->setId((int)$map['id']);
		}

		if (isset($map['accessToken'])) {
			$this->setAccessToken($map['accessToken']);
		}
	}

	/**
	 * @return array
	 */
	public function toMap()
	{
		if ($this->validate()) {
			return [
				'id' => $this->getId(),
				'accessToken' => $this->getAccessToken(),
			];
		}

		return [];
	}
}