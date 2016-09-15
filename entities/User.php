<?php

namespace entities;

class User extends BaseEntity
{
	/**
	 * @var int
	 */
	private $id;

	/**
	 * @var string
	 */
	private $accessToken;

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