<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Email implements DataTypeInterface
{
    use DataTypeFilterTrait;
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_MAIL_ADDRESS_FORMAT';
    protected $filterFlags = [FILTER_VALIDATE_EMAIL];
    protected $sanitizeFlags = FILTER_SANITIZE_EMAIL;
    protected static $defaults = [
        'default'   => ''
    ];
}
