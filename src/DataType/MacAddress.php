<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class MacAddress implements DataTypeInterface
{
    use DataTypeFilterTrait;
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_MAC_ADDRESS_FORMAT';
    protected $filterFlags = [FILTER_VALIDATE_MAC];
    protected static $defaults = [
        'default'   => null
    ];
}
