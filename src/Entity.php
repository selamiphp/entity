<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use JsonSerializable;
use Selami\Entity\Exception\UnexpectedValueException;

final class Entity implements JsonSerializable
{
    use ObjectTrait;

    public function __construct(Model $model, string $id, ?stdClass $data = null)
    {
        $this->model = $model;
        $this->data = $data;
        if ($data === null) {
            $this->data = new stdClass();
        }
        $this->data->id = $id;
    }

    public static function createFromJsonFile($filePath, string $id) : Entity
    {
        if (!file_exists($filePath)) {
            throw new UnexpectedValueException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        $json = file_get_contents($filePath);
        return static::createFromJson($json, $id);
    }

    public static function createFromJson($json, string $id) : Entity
    {
        return new static(new Model($json), $id);
    }
}
