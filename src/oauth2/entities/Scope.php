<?php

namespace oauth2\entities;

use entities\AbstractEntity;
use entities\Entity;

/**
 * Сущность, которая предствляет ограничения для API
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Scope extends AbstractEntity implements Entity
{
	/**
	 * @var id
	 */
	private $id;

	/**
	 * Название разрешения
	 *
	 * @var string
	 */
	private $alias;

	/**
	 * @inheritdoc
	 */
	public function validate()
	{
		if (!$this->alias) {
			$this->errors[] = 'Alias is empty or missing';
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
	public function getAlias()
	{
		return $this->alias;
	}

	/**
	 * @param int $id
	 */
	public function setId($id)
	{
		$this->id = $id;
	}

	/**
	 * @param string $alias
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
	}

	/**
	 * @inheritdoc
	 */
	public function load(array $map)
	{
		if (isset($map['id'])) {
			$this->setId((int)$map['id']);
		}

		if (isset($map['alias'])) {
			$this->setAlias($map['alias']);
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
				'alias' => $this->getAlias(),
			];
		}

		return [];
	}
}