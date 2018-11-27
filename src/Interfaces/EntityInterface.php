<?php
declare(strict_types=1);

namespace Selami\Entity\Interfaces;

use JsonSerializable;

interface EntityInterface extends JsonSerializable
{
    public function validate() : bool;
    public function equals(object $object) : bool;
    public static function createFromJsonFile(string $filePath, string $id) : EntityInterface;
    public static function createFromJson(string $json, string $id) : EntityInterface;
    public function validatePartially(array $requiredFields) : bool;
}