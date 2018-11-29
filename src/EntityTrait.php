<?php
declare(strict_types=1);

namespace Selami\Entity;

use Selami\Entity\Interfaces\EntityInterface;
use stdClass;
use Selami\Entity\Exception\SettingValueForInvalidPropertyException;
use Selami\Entity\Exception\OverridingIdentityOfEntityException;
use Selami\Entity\Exception\CouldNotFindJSONSchemaFileException;
use function in_array;

trait EntityTrait
{
    use ObjectTrait;

    final public function __construct(Model $model, string $id, ?stdClass $data = null)
    {
        $this->model = $model;
        $this->data = $data;
        if ($data === null) {
            $this->data = new stdClass();
        }
        $this->data->id = $id;
    }

    final public function __set($name, $value) : void
    {
        if ($name === 'id') {
            throw new OverridingIdentityOfEntityException('You can not change the "id" of an entity!');
        }
        if (!in_array($name, $this->model->getProperties(), true)) {
            throw new SettingValueForInvalidPropertyException('You can not set value for invalid property!');
        }
        $this->data->{$name} = $value;
    }

    final public function __unset($name)
    {
        unset($this->data->{$name});
    }

    final public static function createFromJsonFile(string $jsonFilePath, string $id) : EntityInterface
    {
        if (!file_exists($jsonFilePath)) {
            throw new CouldNotFindJSONSchemaFileException(
                sprintf('Json Schema file(%s) does not exist!', $jsonFilePath)
            );
        }
        $json = file_get_contents($jsonFilePath);
        return self::createFromJson($json, $id);
    }

    final public static function createFromJson(string $json, string $id) : EntityInterface
    {
        return new static(new Model($json), $id);
    }

    final public function entityId() : string
    {
        return $this->data->id;
    }

    final public function validatePartially(array $requiredFields) : bool
    {
        $model = $this->model->getModel();
        $model->required = $requiredFields;
        $schema = $this->model->getSchema($model);
        return $this->validateData($this->data, $schema);
    }
}
