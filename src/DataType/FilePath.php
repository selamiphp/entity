<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;
use InvalidArgumentException;

class FilePath extends DataTypeAbstract implements DataTypeInterface
{
    use DataTypeRegexTrait;
    const DATA_FORMAT_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';
    protected $regex = '#^[a-zA-Z0-9/._-]+$#';
    protected $sanitizeFlags = FILTER_SANITIZE_URL;
    protected static $defaults = [
        'default'   => null
    ];
}
