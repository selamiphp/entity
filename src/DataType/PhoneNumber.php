<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class PhoneNumber implements DataTypeInterface
{
    use DataTypeRegexTrait;
    protected $regex = '/^\+?[1-9]\d{1,14}$/';
    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_FORMAT';
    protected static $defaults = [
        'default'   => null
    ];
}
