<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use Selami\Entity\Exception\UnexpectedValueException;
use Selami\Entity\Exception\BadMethodCallException;

trait ValueObjectTrait
{
    use ObjectTrait;

    public function __construct(Model $model, stdClass $data)
    {
        $this->model = $model;
        $this->data = $data;
        $this->validate();
    }

    final public function __set($name, $value) : void
    {
        throw new BadMethodCallException('Can\'t manipulate Immutable Object');
    }

    final public function __unset($name)
    {
        throw new BadMethodCallException('Can\'t manipulate Immutable Object');
    }

    public static function createFromJsonFile($filePath, stdClass $data) : ValueObject
    {
        if (!file_exists($filePath)) {
            throw new UnexpectedValueException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        $json = file_get_contents($filePath);
        return static::createFromJson($json, $data);
    }

    public static function createFromJson($json, stdClass $data) : ValueObject
    {
        return new static(new Model($json), $data);
    }
}
