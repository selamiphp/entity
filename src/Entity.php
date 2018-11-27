<?php
declare(strict_types=1);
namespace Selami\Entity;

use JsonSerializable;

class Entity implements JsonSerializable
{
    use EntityTrait;
}
