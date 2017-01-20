<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Ipv4 implements DataTypeInterface
{
    use DataTypeFilterTrait;
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_IP_V4_FORMAT';
    protected $filterFlags = [FILTER_VALIDATE_IP , FILTER_FLAG_IPV4];
    protected static $defaults = [
        'default'   => null
    ];
}
