<?php
declare(strict_types=1);

namespace Selami\Entity\Interfaces;

use stdClass;
use JsonSerializable;

interface ValueObjectInterface extends JsonSerializable
{
    public function validate() : bool;
    public function equals(object $object) : bool;
    public static function createFromJsonFile(string $filePath, stdClass $data) : ValueObjectInterface;
    public static function createFromJson(string $json, stdClass $data) : ValueObjectInterface;
}