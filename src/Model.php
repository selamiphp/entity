<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use Opis\JsonSchema\Schema;
use Selami\Entity\Exception\InvalidArgumentException;

final class Model
{
    private $model;
    private $schema;
    private $requiredFields;
    private $properties;

    public function __construct(string $jsonSchema)
    {
        $this->model = json_decode($jsonSchema);
        if (json_last_error() === JSON_ERROR_NONE) {
            $this->requiredFields = $this->model->required;
            $this->schema = $this->getSchema($this->model);
            $this->extractValidProperties();
        }
    }

    public function getModel() : stdClass
    {
        return $this->model;
    }

    public function getRequiredFields() : array
    {
        return $this->requiredFields;
    }

    public function getProperties() : array
    {
        return $this->properties;
    }

    public function extractValidProperties() : void
    {
        $rootProperties = get_object_vars($this->schema->resolve()->properties);
        $this->properties = $this->extractSubProperties($rootProperties);
    }

    private function extractSubProperties($rootProperties) : array
    {
        return array_keys($rootProperties);
    }

    public function getSchema(?stdClass $model = null)  : Schema
    {
        if ($model !== null) {
            return new Schema($model);
        }
        return new Schema($this->model);
    }

    public static function createFromJsonFile(string $filePath) : Model
    {
        if (!file_exists($filePath)) {
            throw new InvalidArgumentException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        return new static(file_get_contents($filePath));
    }
}
