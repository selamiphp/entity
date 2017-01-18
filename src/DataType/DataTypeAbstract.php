<?php

namespace Selami\Entity\DataType;

use InvalidArgumentException;

abstract class DataTypeAbstract
{
    /**
     * @var string
     */
    protected $key;
    /**
     * @var mixed
     */
    protected $datum;
    /**
     * @var array
     */
    protected $options = [];

    protected $errorMessageTemplate;

    protected function throwException()
    {
        $message = sprintf(
            $this->errorMessageTemplate,
            $this->datum,
            $this->key
        );
        throw new InvalidArgumentException($message);
    }
}
