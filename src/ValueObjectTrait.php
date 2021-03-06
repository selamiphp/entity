<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use Selami\Entity\Exception\InvalidArgumentException;
use Selami\Entity\Exception\BadMethodCallException;
use Selami\Entity\Interfaces\ValueObjectInterface;

trait ValueObjectTrait
{
    use ObjectTrait;

    final public function __construct(Model $model, stdClass $data)
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

    final public static function createFromJsonFile(string $filePath, stdClass $data) : ValueObjectInterface
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        $json = file_get_contents($filePath);
        return self::createFromJson($json, $data);
    }

    final public static function createFromJson(string $json, stdClass $data) : ValueObjectInterface
    {
        return new static(new Model($json), $data);
    }
}
