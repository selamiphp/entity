<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Slug implements DataTypeInterface
{
    use DataTypeRegexTrait;
    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';
    protected $regex = '/^[a-z0-9](-?[a-z0-9]+)*$/i';
    protected $sanitizeFlags = FILTER_SANITIZE_URL;
    protected static $defaults = [
        'default' => null
    ];
}
