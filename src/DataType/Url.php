<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Url implements DataTypeInterface
{
    use DataTypeFilterTrait;

    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';
    protected $filterFlags = [FILTER_VALIDATE_URL];
    protected $sanitizeFlags = FILTER_SANITIZE_URL;
    protected static $defaults = [
        'default' => null
    ];
}
