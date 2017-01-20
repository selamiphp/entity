<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Uuid implements DataTypeInterface
{
    use DataTypeRegexTrait;
    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_FORMAT';
    protected $regex = '/^[0-9A-Fa-f]{8}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{4}-[0-9A-Fa-f]{12}$/';
    protected static $defaults = [
        'default'   => '00000000-0000-0000-0000-000000000000'
    ];
}
