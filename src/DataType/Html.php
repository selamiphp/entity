<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use Selami\Entity\Interfaces\DataTypeInterface;

class Html implements DataTypeInterface
{
    use DataTypeFilterTrait;
    const DATA_TYPE_ERROR   = 'Assertion failed for value "%s" for "%s" : INVALID_TYPE';

    protected static $defaults = [
        'default'   => null
    ];

    /**
     * {@inheritdoc}
     */
    public function assert()
    {
        $this->isString();
        return true;
    }

    private function isString()
    {
        if (!is_string($this->datum)) {
            $this->errorMessageTemplate = self::DATA_TYPE_ERROR;
            $this->throwException();
        }
        return true;
    }
}
