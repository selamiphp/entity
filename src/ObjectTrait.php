<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use Opis\JsonSchema\Validator;
use Selami\Entity\Exception\InvalidArgumentException;

trait ObjectTrait
{
    /**
     * @var Model
     */
    private $model;

    /*
     * @var stdClass
     */
    private $data;

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
        return $this->validateData($this->data, $this->model->getSchema());
    }

    public function validatePartially(array $requiredFields) : bool
    {
        $model = $this->model->getModel();
        $model->required = $requiredFields;
        $schema = $this->model->getSchema($model);
        return $this->validateData($this->data, $schema);
    }

    private function validateData($data, $schema) : bool
    {
        $validation = (new Validator())->schemaValidation($data, $schema);
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

    public function jsonSerialize() : stdClass
    {
        return $this->data;
    }

    public function __toString() : string
    {
        return (string) json_encode($this);
    }
}
