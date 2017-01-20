<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Integer implements DataTypeInterface
{
    use DataTypeFilterTrait;
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';
    protected $sanitizeFlags = FILTER_SANITIZE_NUMBER_INT;
    protected $filterFlags = [FILTER_VALIDATE_INT];
    protected static $defaults = [
        'default' => 0
    ];


    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        if (!is_int($this->datum)) {
            $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
            $this->throwException();
        }
        return true;
    }
}
