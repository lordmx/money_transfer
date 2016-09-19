<?php

namespace entities;

/**
 * Реализация абстрактной сущности предметной области
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
abstract class AbstractEntity
{
    /**
     * @var string[]
     */
    protected $dirty = [];

    /**
     * @var string[]
     */
    protected $errors = [];

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @param string $field
     */
    public function addDirty($field)
    {
        $this->dirty[] = $field;
    }

    /**
     * @return array
     */
    public function getDirty()
    {
        return $this->dirty;
    }
}