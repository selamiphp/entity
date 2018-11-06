<?php
declare(strict_types=1);

namespace Selami\Entity;

use Opis\JsonSchema\Schema;
use Selami\Entity\Exception\UnexpectedValueException;

final class Model
{
    private $schema;

    public function __construct(string $jsonSchema)
    {
        $this->schema = Schema::fromJsonString($jsonSchema);
    }

    public function getSchema()  : Schema
    {
        return $this->schema;
    }

    public static function fromJsonFile(string $filePath) : Model
    {
        if (!file_exists($filePath)) {
            throw new UnexpectedValueException(sprintf('Model definition file (%s) does not exist!', $filePath));
        }
        return new static(file_get_contents($filePath));
    }
}
