<?php

namespace api;

use entities\Entity;
use helpers\HelperString;

/**
 * Результат выполнения API-метода
 *
 * @author Ilya Kolesnikov <fatumm@gmail.com>
 */
class Result
{
    /**
     * Название REST-ресурса
     *
     * @var string
     */
    private $resource;

    /**
     * Сущности
     *
     * @var array
     */
    private $vector = [];

    /**
     * Метаданные
     *
     * @var Metadata
     */
    private $metadata;

    /**
     * @param Metadata $metadata
     * @param string $resource
     * @param array $vector
     */
    public function __construct(Metadata $metadata, $resource, array $vector = [])
    {
        $this->resource = $resource;
        $this->metadata = $metadata;
        $this->vector = $vector;
    }

    /**
     * @return string
     */
    public function toJson()
    {
        $vector = $this->vector;

        foreach ($vector as $i => $item) {
            if ($item instanceof Entity) {
                $map = $item->toMap();

                foreach ($map as $key => $value) {
                    unset($map[$key]);
                    $map[HelperString::toUnderscore($key)] = $value;
                }

                $vector[$i] = $map;
            }
        }

        $raw = [
            'resultset' => [
                'metadata' => $this->metadata->toMap(),
            ],
            $this->resource => $vector,
        ];

        return json_encode($raw);
    }
}