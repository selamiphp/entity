<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Double implements DataTypeInterface
{
    use DataTypeFilterTrait;
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';

    protected static $defaults = [
        'default' => 0.0
    ];


    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        if (!is_float($this->datum)) {
            $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
            $this->throwException();
        }
        return true;
    }
}
