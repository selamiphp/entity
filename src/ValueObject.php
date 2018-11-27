<?php
declare(strict_types=1);

namespace Selami\Entity;

use JsonSerializable;

class ValueObject implements JsonSerializable
{
    use ValueObjectTrait;
}
