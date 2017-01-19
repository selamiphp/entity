<?php
declare(strict_types=1);

namespace Selami\Entity\DataType;

use InvalidArgumentException;

abstract class DataTypeAbstract
{
    const INVALID_OPTIONS = 'Option: %s is invalid. You should remove it or check for typo.';
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

    protected function checkValidOptions(array $options)
    {
        $validOptions = array_keys($this::$defaults);
        foreach ($options as $optionKey => $optionValue) {
            if (!in_array($optionKey, $validOptions, true)) {
                throw new InvalidArgumentException(sprintf(self::INVALID_OPTIONS, $optionKey));
            }
        }
    }

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
