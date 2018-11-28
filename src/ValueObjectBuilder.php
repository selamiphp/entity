<?php
declare(strict_types=1);

namespace Selami\Entity;

use stdClass;
use Selami\Entity\Interfaces\ValueObjectInterface;
use Selami\Entity\Exception\CouldNotFindJSONSchemaFileException;
use Selami\Entity\Exception\InvalidMethodNameException;
use function in_array;

class ValueObjectBuilder
{
    private $jsonSchema;
    private $properties;
    private $data;

    private function __construct(string $jsonSchema)
    {
        $this->data = new stdClass();
        $this->jsonSchema = $jsonSchema;
        $this->properties = (new Model($jsonSchema))->getProperties();
    }

    public static function createFromJsonFile(string $jsonFilePath) : ValueObjectBuilder
    {
        if (!file_exists($jsonFilePath)) {
            throw new CouldNotFindJSONSchemaFileException(
                sprintf('Json Schema file(%s) does not exist!', $jsonFilePath)
            );
        }
        return self::createFromJsonString(file_get_contents($jsonFilePath));
    }
    public static function createFromJsonString(string $jsonString) : ValueObjectBuilder
    {
        return new self($jsonString);
    }

    public function __call(string $name, array $values)
    {
        if (strpos($name, 'with') !== 0) {
            throw new InvalidMethodNameException(
                sprintf('Invalid method name (%s) to set a value to a property', $name)
            );
        }
        $propertyName = lcfirst(str_replace('with', '', $name));
        if (!in_array($propertyName, $this->properties, true)) {
            throw new InvalidMethodNameException(
                sprintf('Invalid property name (%s) for the defined schema!', $propertyName)
            );
        }
        $this->data->{$propertyName} = $values[0];
        return $this;
    }

    public function build() : ValueObjectInterface
    {
        return ValueObject::createFromJson($this->jsonSchema, $this->data);
    }
}
