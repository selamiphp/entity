<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use JsonSerializable;
use Selami\Entity\Exception\UnexpectedValueException;

final class ValueObject implements JsonSerializable
{
    use ObjectTrait;

    public function __construct(Model $model, ?stdClass $data = null)
    {
        $this->model = $model;
        $this->data = $data;
        if ($data === null) {
            $this->data = new stdClass();
        }
    }

    public static function createFromJsonFile($filePath) : ValueObject
    {
        if (!file_exists($filePath)) {
            throw new UnexpectedValueException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        $json = file_get_contents($filePath);
        return static::createFromJson($json);
    }
    public static function createFromJson($json) : ValueObject
    {
        return new static(new Model($json));
    }
}
