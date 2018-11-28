<?php
declare(strict_types=1);

namespace Selami\Entity;

use Selami\Entity\Exception\InvalidArgumentException;
use stdClass;
use Selami\Entity\Interfaces\EntityInterface;

trait EntityTrait
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

    public function __set($name, $value) : void
    {
        if ($name === 'id') {
            throw new InvalidArgumentException('You can not change the "id" of an entity!');
        }
        $this->data->{$name} = $value;
    }

    public function __unset($name)
    {
        unset($this->data->{$name});
    }

    public static function createFromJsonFile(string $filePath, string $id) : EntityInterface
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        $json = file_get_contents($filePath);
        return static::createFromJson($json, $id);
    }

    public static function createFromJson(string $json, string $id) : EntityInterface
    {
        return new static(new Model($json), $id);
    }

    public function entityId() : string
    {
        return $this->data->id;
    }

    public function validatePartially(array $requiredFields) : bool
    {
        $model = $this->model->getModel();
        $model->required = $requiredFields;
        $schema = $this->model->getSchema($model);
        return $this->validateData($this->data, $schema);
    }
}
