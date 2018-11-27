<?php
declare(strict_types=1);

namespace Selami\Entity;

use Selami\Entity\Interfaces\ValueObjectInterface;

class ValueObject implements ValueObjectInterface
{
    use ValueObjectTrait;
}
