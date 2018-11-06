<?php
declare(strict_types=1);

namespace Selami\Entity;

use JsonSerializable;
use stdClass;
use Opis\JsonSchema\Validator;
use Selami\Entity\Exception\InvalidArgumentException;
use Selami\Entity\Exception\UnexpectedValueException;
use Ramsey\Uuid\Uuid;

final class Entity implements JsonSerializable
{
    /**
     * @var Model
     */
    private $model;
    /*
     * @var stdClass
     */
    private $data;

    public function __construct(Model $model, ?stdClass $data = null)
    {
        $this->model = $model;
        $this->data = $data;
        if ($data === null) {
            $this->data = new stdClass();
        }
        $this->checkAndSetId();
    }

    private function checkAndSetId() : void
    {
        if (!isset($this->data->id)) {
            $this->data->id = Uuid::uuid4()->toString();
        }
    }

    public function __get($name)
    {
        return $this->data->{$name};
    }

    public function __set($name, $value) : void
    {
        $this->data->{$name} = $value;
    }

    public function __isset($name) : bool
    {
        return property_exists($this->data, $name);
    }

    public function __unset($name)
    {
        unset($this->data->{$name});
    }

    public function validate() : bool
    {
        $validation = (new Validator())->schemaValidation($this->data, $this->model->getSchema());
        if (!$validation->isValid()) {
            $errors = $validation->getErrors();
            $message = 'Data validation failed.' . PHP_EOL;
            foreach ($errors as $error) {
                $message .= sprintf(
                    'ERROR: %s. %s',
                    $error->keyword(),
                    json_encode($error->keywordArgs(), JSON_PRETTY_PRINT)
                ) . PHP_EOL;
            }
            throw new InvalidArgumentException(
                $message
            );
        }
        return true;
    }

    public function equals($rightHandedObject) : bool
    {
        return (string) $this === (string) $rightHandedObject;
    }

    public function jsonSerialize() : string
    {
        return (string) json_encode($this->data);
    }

    public function __toString()
    {
        return $this->jsonSerialize();
    }

    public static function createFromJsonFile($filePath) : Entity
    {
        if (!file_exists($filePath)) {
            throw new UnexpectedValueException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        $json = file_get_contents($filePath);
        return self::createFromJson($json);
    }

    public static function createFromJson($json) : Entity
    {
        return new static(new Model($json));
    }
}
