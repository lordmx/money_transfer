<?php

namespace entities;

/**
 * Базовый интерфейс сущности предметной области системы.
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
interface Entity
{
	/**
	 * Получить значения идентификатора сущности (для простоты считаем, что все идентификаторы целочисленные)
	 *
	 * @return int
	 */
	public function getId();

	/**
	 * Установить значения идентификатора сущности 
	 *
	 * @param int $id
	 */
	public function setId($id);

	/**
	 * Получить названия полей, которые были изменены у сущности 
	 *
	 * @return array
	 */
	public function getDirty();

	/**
	 * Выполнить валидацию сущности 
	 *
	 * @return bool
	 */
	public function validate();

	/**
	 * Получить ошибки последней валидации 
	 *
	 * @return array
	 */
	public function getErrors();

	/**
	 * Построить объект из ассоциативного массива
	 * 
	 * @param array $map
	 */
	public function load(array $map);

	/**
	 * Свернуть объект в ассоциативный массив
	 *
	 * @return array
	 */
	public function toMap();
}