<?php

namespace entities;

interface Entity
{
	/**
	 * @return int
	 */
	public function getId();

	/**
	 * @param int $id
	 */
	public function setId($id);

	/**
	 * @return array
	 */
	public function getDirty();

	/**
	 * @return bool
	 */
	public function validate();

	/**
	 * @return array
	 */
	public function getErrors();

	/**
	 * @param array $map
	 */
	public function load(array $map);

	/**
	 * @return array
	 */
	public function toMap();
}